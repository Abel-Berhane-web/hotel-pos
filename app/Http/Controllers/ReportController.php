<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RoomRental;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $orders = Order::with('items.product.category', 'cashier', 'employee')->whereDate('order_date', $date)->get();
        $rentals = RoomRental::with('room')->whereDate('check_in', $date)->get();

        $drinkSales = $orders->sum(fn($o) => $o->items->filter(fn($i) => $i->product->category->type === 'drink')->sum('line_total'));
        $foodSales = $orders->sum(fn($o) => $o->items->filter(fn($i) => $i->product->category->type === 'food')->sum('line_total'));
        $bedIncome = $rentals->sum('total_price');
        $totalRevenue = $orders->sum('total') + $bedIncome;

        $paymentBreakdown = [];
        foreach (['cash', 'bank_transfer', 'telebirr', 'cbe_birr', 'credit'] as $method) {
            $paymentBreakdown[$method] = $orders->where('payment_method', $method)->sum('total')
                + $rentals->where('payment_method', $method)->sum('total_price');
        }

        return view('reports.daily', compact('date', 'orders', 'rentals', 'drinkSales', 'foodSales', 'bedIncome', 'totalRevenue', 'paymentBreakdown'));
    }

    public function period(Request $request)
    {
        $preset = $request->get('preset', 'last_15');
        $from = $request->get('from');
        $to = $request->get('to');

        if (!$from || !$to) {
            [$from, $to] = match($preset) {
                'last_15' => [now()->subDays(14)->format('Y-m-d'), today()->format('Y-m-d')],
                'last_30' => [now()->subDays(29)->format('Y-m-d'), today()->format('Y-m-d')],
                'this_month' => [now()->startOfMonth()->format('Y-m-d'), today()->format('Y-m-d')],
                default => [now()->subDays(14)->format('Y-m-d'), today()->format('Y-m-d')],
            };
        }

        $orders = Order::with('items.product.category')
            ->whereDate('order_date', '>=', $from)
            ->whereDate('order_date', '<=', $to)->get();
        $rentals = RoomRental::whereDate('check_in', '>=', $from)->whereDate('check_in', '<=', $to)->get();

        $totalRevenue = $orders->sum('total') + $rentals->sum('total_price');
        $drinkSales = $orders->sum(fn($o) => $o->items->filter(fn($i) => $i->product->category->type === 'drink')->sum('line_total'));
        $foodSales = $orders->sum(fn($o) => $o->items->filter(fn($i) => $i->product->category->type === 'food')->sum('line_total'));
        $bedIncome = $rentals->sum('total_price');

        $paymentBreakdown = [];
        foreach (['cash', 'bank_transfer', 'telebirr', 'cbe_birr', 'credit'] as $method) {
            $paymentBreakdown[$method] = $orders->where('payment_method', $method)->sum('total')
                + $rentals->where('payment_method', $method)->sum('total_price');
        }

        // Daily breakdown
        $dailyData = [];
        $period = new \DatePeriod(new \DateTime($from), new \DateInterval('P1D'), (new \DateTime($to))->modify('+1 day'));
        foreach ($period as $day) {
            $d = $day->format('Y-m-d');
            $dayOrders = $orders->filter(fn($o) => $o->order_date->format('Y-m-d') === $d);
            $dayRentals = $rentals->filter(fn($r) => $r->check_in->format('Y-m-d') === $d);
            $dailyData[] = [
                'date' => $d,
                'orders' => $dayOrders->count(),
                'order_total' => $dayOrders->sum('total'),
                'bed_total' => $dayRentals->sum('total_price'),
                'total' => $dayOrders->sum('total') + $dayRentals->sum('total_price'),
            ];
        }

        // Top products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereHas('order', fn($q) => $q->whereDate('order_date', '>=', $from)->whereDate('order_date', '<=', $to))
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(10)->with('product')->get();

        return view('reports.period', compact('from', 'to', 'preset', 'totalRevenue', 'drinkSales', 'foodSales', 'bedIncome', 'paymentBreakdown', 'dailyData', 'topProducts'));
    }

    public function productSales(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', today()->format('Y-m-d'));

        $products = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereHas('order', fn($q) => $q->whereDate('order_date', '>=', $from)->whereDate('order_date', '<=', $to))
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->with('product.category')->paginate(30);

        return view('reports.product-sales', compact('from', 'to', 'products'));
    }

    public function stock()
    {
        $products = Product::where('is_active', true)->where('track_stock', true)
            ->with('category')->orderBy('stock_quantity')->get();
        return view('reports.stock', compact('products'));
    }
}
