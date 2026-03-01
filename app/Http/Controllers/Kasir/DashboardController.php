<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->startOfDay();
        $todaySales = Sale::query()->where('sold_at', '>=', $today);
        $locationSummary = (clone $todaySales)
            ->selectRaw('stand_location, COUNT(*) as sales_count, COALESCE(SUM(total), 0) as sales_total')
            ->groupBy('stand_location')
            ->pluck('sales_total', 'stand_location');

        return view('kasir.dashboard', [
            'salesCount' => (clone $todaySales)->count(),
            'salesTotal' => (clone $todaySales)->sum('total'),
            'locationTotals' => [
                'NICC' => (float) ($locationSummary['NICC'] ?? 0),
                'GRASA' => (float) ($locationSummary['GRASA'] ?? 0),
            ],
            'latestSales' => Sale::with('user')->latest('sold_at')->limit(8)->get(),
        ]);
    }
}
