<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Services\FonnteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category_id');

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($search, fn ($q) => $q->where(function ($inner) use ($search) {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('kasir.pos.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'cart' => $this->cart(),
            'summary' => $this->cartSummary(),
            'activeStandLocation' => $this->standLocation(),
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::where('is_active', true)->findOrFail($data['product_id']);
        $qty = (int) ($data['qty'] ?? 1);

        $cart = $this->cart();
        $currentQty = (int) ($cart[$product->id]['qty'] ?? 0);
        $newQty = $currentQty + $qty;

        if ($newQty > $product->stock) {
            return back()->withErrors(['stock' => "Stok {$product->name} tidak mencukupi."]);
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'cost_price' => (float) $product->cost_price,
            'price' => (float) $product->price,
            'qty' => $newQty,
            'stock' => $product->stock,
        ];

        $this->saveCart($cart);

        return back()->with('success', "{$product->name} masuk ke keranjang.");
    }

    public function updateCart(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:0'],
        ]);

        $cart = $this->cart();
        $productId = (int) $data['product_id'];

        if (! isset($cart[$productId])) {
            return back();
        }

        if ((int) $data['qty'] === 0) {
            unset($cart[$productId]);
            $this->saveCart($cart);

            return back()->with('success', 'Item keranjang dihapus.');
        }

        $product = Product::findOrFail($productId);
        if ((int) $data['qty'] > $product->stock) {
            return back()->withErrors(['stock' => "Stok {$product->name} tersisa {$product->stock}."]);
        }

        $cart[$productId]['qty'] = (int) $data['qty'];
        $cart[$productId]['stock'] = (int) $product->stock;
        $this->saveCart($cart);

        return back()->with('success', 'Jumlah item diperbarui.');
    }

    public function clearCart(): RedirectResponse
    {
        session()->forget('pos_cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    public function setStandLocation(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'stand_location' => ['required', 'in:NICC,GRASA'],
        ]);

        session(['pos_stand_location' => $data['stand_location']]);

        return back()->with('success', 'Lokasi stand aktif diperbarui.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $request->merge([
            'stand_location' => $request->input('stand_location', $this->standLocation()),
        ]);

        $data = $request->validate([
            'stand_location' => ['required', 'in:NICC,GRASA'],
            'payment_method' => ['required', 'in:tunai,transfer,qris'],
            'paid' => ['nullable', 'numeric', 'min:0'],
            'payment_proof' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'buyer_name' => ['nullable', 'string', 'max:120'],
            'buyer_whatsapp' => ['nullable', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = $this->cart();
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Keranjang kosong.']);
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $total = (float) $subtotal;
        $paymentMethod = (string) $data['payment_method'];
        $paid = $paymentMethod === 'tunai'
            ? (float) ($data['paid'] ?? 0)
            : $total;

        if ($paymentMethod === 'tunai' && $paid < $total) {
            return back()->withErrors(['paid' => 'Uang bayar kurang dari total transaksi.']);
        }

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        try {
            $sale = DB::transaction(function () use ($cart, $subtotal, $total, $paid, $paymentMethod, $paymentProofPath, $data) {
                $invoiceNo = 'INV-'.now()->format('Ymd-His').'-'.str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
                $totalCost = 0;
                $totalMargin = 0;

                $sale = Sale::create([
                    'user_id' => auth()->id(),
                    'stand_location' => $data['stand_location'],
                    'invoice_no' => $invoiceNo,
                    'sold_at' => now(),
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'total' => $total,
                    'total_cost' => 0,
                    'total_margin' => 0,
                    'paid' => $paid,
                    'change_amount' => $paymentMethod === 'tunai' ? $paid - $total : 0,
                    'payment_method' => $paymentMethod,
                    'payment_proof_path' => $paymentProofPath,
                    'buyer_name' => $data['buyer_name'] ?? null,
                    'buyer_whatsapp' => $data['buyer_whatsapp'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);

                foreach ($cart as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                    if ($product->stock < $item['qty']) {
                        throw new \RuntimeException("Stok {$product->name} tidak cukup untuk checkout.");
                    }

                    $product->decrement('stock', $item['qty']);

                    $itemSubtotal = $item['price'] * $item['qty'];
                    $itemSubtotalCost = (float) $product->cost_price * $item['qty'];
                    $itemSubtotalMargin = $itemSubtotal - $itemSubtotalCost;
                    $totalCost += $itemSubtotalCost;
                    $totalMargin += $itemSubtotalMargin;

                    $sale->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $item['price'],
                        'cost_price' => $product->cost_price,
                        'qty' => $item['qty'],
                        'subtotal' => $itemSubtotal,
                        'subtotal_cost' => $itemSubtotalCost,
                        'subtotal_margin' => $itemSubtotalMargin,
                    ]);
                }

                $sale->update([
                    'total_cost' => $totalCost,
                    'total_margin' => $totalMargin,
                ]);

                return $sale;
            });
        } catch (\RuntimeException $e) {
            if ($paymentProofPath) {
                Storage::disk('public')->delete($paymentProofPath);
            }

            return back()->withErrors(['cart' => $e->getMessage()]);
        }

        session()->forget('pos_cart');

        $this->sendReceiptWhatsApp($sale);

        return redirect()->route('kasir.sales.show', $sale)->with('success', 'Transaksi berhasil disimpan.');
    }

    public function sales(): View
    {
        return view('kasir.pos.sales', [
            'sales' => Sale::with('user')->latest('sold_at')->paginate(15),
        ]);
    }

    public function showSale(Sale $sale): View
    {
        $sale->load(['items', 'user']);

        return view('kasir.pos.receipt', compact('sale'));
    }

    public function sendWhatsApp(Sale $sale): RedirectResponse
    {
        $sent = $this->sendReceiptWhatsApp($sale);

        return back()->with(
            $sent ? 'success' : 'error',
            $sent ? 'Nota berhasil terkirim ke WhatsApp.' : 'Gagal mengirim nota ke WhatsApp. Cek nomor atau token Fonte.'
        );
    }

    private function cart(): array
    {
        return session('pos_cart', []);
    }

    private function standLocation(): string
    {
        $standLocation = (string) session('pos_stand_location', 'NICC');

        return in_array($standLocation, ['NICC', 'GRASA'], true) ? $standLocation : 'NICC';
    }

    private function saveCart(array $cart): void
    {
        session(['pos_cart' => $cart]);
    }

    private function cartSummary(): array
    {
        $cart = $this->cart();
        $subtotal = (float) collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);

        return [
            'items' => (int) collect($cart)->sum('qty'),
            'subtotal' => $subtotal,
        ];
    }

    private function sendReceiptWhatsApp(Sale $sale): bool
    {
        $target = $this->normalizeWhatsAppNumber((string) ($sale->buyer_whatsapp ?? ''));
        if ($target === '') {
            return false;
        }

        try {
            $sale->loadMissing('items');

            $lines = [
                'Halo '.($sale->buyer_name ?: 'Pelanggan').',',
                'Terima kasih sudah berbelanja di POS DSCM.',
                '',
                'No Nota: '.$sale->invoice_no,
                'Tanggal: '.$sale->sold_at->format('d-m-Y H:i'),
                'Stand: '.$sale->stand_location,
                'Metode: '.strtoupper($sale->payment_method),
                'Total: Rp '.number_format((float) $sale->total, 0, ',', '.'),
                '',
                'Detail item:',
            ];

            foreach ($sale->items as $item) {
                $lines[] = '- '.$item->product_name.' x'.$item->qty.' = Rp '.number_format((float) $item->subtotal, 0, ',', '.');
            }

            $lines[] = '';
            $lines[] = 'Terima kasih.';

            app(FonnteService::class)->sendText($target, implode("\n", $lines));

            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal kirim nota WhatsApp otomatis', [
                'sale_id' => $sale->id,
                'buyer_whatsapp' => $sale->buyer_whatsapp,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function normalizeWhatsAppNumber(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        if ($phone === '') {
            return '';
        }

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        if (! str_starts_with($phone, '62')) {
            $phone = '62'.$phone;
        }

        return $phone;
    }
}
