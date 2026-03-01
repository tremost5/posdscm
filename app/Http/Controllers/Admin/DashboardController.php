<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();

        return view('admin.dashboard', [
            'stats' => [
                'categories' => Category::count(),
                'products' => Product::count(),
                'suppliers' => Supplier::count(),
                'cashiers' => User::where('role', 'kasir')->count(),
                'today_sales' => Sale::where('sold_at', '>=', $today)->sum('total'),
                'today_margin' => Sale::where('sold_at', '>=', $today)->sum('total_margin'),
            ],
            'recentSales' => Sale::with('user')->latest('sold_at')->limit(8)->get(),
        ]);
    }
}
