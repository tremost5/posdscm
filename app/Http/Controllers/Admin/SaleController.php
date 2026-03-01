<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesReportExport;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->resolveFilters($request);
        $salesQuery = $this->buildSalesQuery($filters['start'], $filters['end'], $filters['stand_location']);

        return view('admin.sales.index', [
            'sales' => $salesQuery->with('user')->latest('sold_at')->paginate(15)->withQueryString(),
            'summary' => [
                'revenue' => (clone $salesQuery)->sum('total'),
                'cost' => (clone $salesQuery)->sum('total_cost'),
                'margin' => (clone $salesQuery)->sum('total_margin'),
            ],
            'filters' => $filters,
        ]);
    }

    public function show(Sale $sale): View
    {
        $sale->load(['items', 'user']);

        return view('admin.sales.show', compact('sale'));
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $this->resolveFilters($request);
        $sales = $this->buildSalesQuery($filters['start'], $filters['end'], $filters['stand_location'])
            ->with('user')
            ->latest('sold_at')
            ->get();

        $filename = sprintf(
            'laporan-penjualan-%s-%s.xlsx',
            $filters['period'],
            $filters['date']
        );

        return Excel::download(new SalesReportExport($sales), $filename);
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->resolveFilters($request);
        $salesQuery = $this->buildSalesQuery($filters['start'], $filters['end'], $filters['stand_location']);
        $sales = (clone $salesQuery)->with('user')->latest('sold_at')->get();

        $pdf = Pdf::loadView('admin.sales.report-pdf', [
            'sales' => $sales,
            'summary' => [
                'revenue' => (clone $salesQuery)->sum('total'),
                'cost' => (clone $salesQuery)->sum('total_cost'),
                'margin' => (clone $salesQuery)->sum('total_margin'),
            ],
            'filters' => $filters,
        ])->setPaper('a4', 'landscape');

        $filename = sprintf(
            'laporan-penjualan-%s-%s.pdf',
            $filters['period'],
            $filters['date']
        );

        return $pdf->download($filename);
    }

    private function buildSalesQuery(Carbon $start, Carbon $end, ?string $standLocation = null): Builder
    {
        return Sale::query()
            ->whereBetween('sold_at', [$start, $end])
            ->when($standLocation, fn (Builder $query) => $query->where('stand_location', $standLocation));
    }

    private function resolveFilters(Request $request): array
    {
        $request->validate([
            'period' => ['nullable', 'in:daily,weekly,monthly'],
            'date' => ['nullable', 'date'],
            'stand_location' => ['nullable', 'in:NICC,GRASA'],
        ]);

        $period = $request->string('period')->toString();
        if (! in_array($period, ['daily', 'weekly', 'monthly'], true)) {
            $period = 'daily';
        }

        $dateInput = $request->input('date');
        $selectedDate = $dateInput ? Carbon::parse($dateInput) : now();

        [$start, $end] = match ($period) {
            'weekly' => [
                $selectedDate->copy()->startOfWeek()->startOfDay(),
                $selectedDate->copy()->endOfWeek()->endOfDay(),
            ],
            'monthly' => [
                $selectedDate->copy()->startOfMonth()->startOfDay(),
                $selectedDate->copy()->endOfMonth()->endOfDay(),
            ],
            default => [
                $selectedDate->copy()->startOfDay(),
                $selectedDate->copy()->endOfDay(),
            ],
        };

        return [
            'period' => $period,
            'date' => $selectedDate->format('Y-m-d'),
            'stand_location' => $request->filled('stand_location') ? $request->string('stand_location')->toString() : null,
            'start' => $start,
            'end' => $end,
        ];
    }
}
