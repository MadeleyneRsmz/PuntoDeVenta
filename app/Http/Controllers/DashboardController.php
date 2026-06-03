<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stats = [
            'products'    => Product::count(),
            'lowStock'    => Product::where('is_active', true)->where('stock', '<=', 5)->count(),
            'salesToday'  => Sale::whereDate('created_at', $today)->count(),
            'incomeToday' => (float) Sale::whereDate('created_at', $today)->sum('total'),
        ];

        $recentSales = Sale::with('cashier')->latest()->take(6)->get();
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentSales', 'lowStockProducts'));
    }
}
