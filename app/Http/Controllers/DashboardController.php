<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Room;
use App\Models\RoomRental;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = today();

        $data = [
            'rooms_available' => Room::where('status', 'available')->count(),
            'rooms_occupied' => Room::where('status', 'occupied')->count(),
        ];

        if ($user->isAdmin() || $user->isManager()) {
            $data['todays_revenue'] = Order::whereDate('order_date', $today)->sum('total')
                + RoomRental::whereDate('check_in', $today)->sum('total_price');
            $data['todays_orders'] = Order::whereDate('order_date', $today)->count();
            $data['drink_sales'] = \App\Models\OrderItem::whereHas('order', fn($q) => $q->whereDate('order_date', $today))
                ->whereHas('product.category', fn($q) => $q->where('type', 'drink'))
                ->sum('line_total');
            $data['food_sales'] = \App\Models\OrderItem::whereHas('order', fn($q) => $q->whereDate('order_date', $today))
                ->whereHas('product.category', fn($q) => $q->where('type', 'food'))
                ->sum('line_total');
            $data['bed_income'] = RoomRental::whereDate('check_in', $today)->sum('total_price');
            $data['payment_breakdown'] = [
                'cash' => Order::whereDate('order_date', $today)->where('payment_method', 'cash')->sum('total'),
                'bank_transfer' => Order::whereDate('order_date', $today)->where('payment_method', 'bank_transfer')->sum('total'),
                'telebirr' => Order::whereDate('order_date', $today)->where('payment_method', 'telebirr')->sum('total'),
                'cbe_birr' => Order::whereDate('order_date', $today)->where('payment_method', 'cbe_birr')->sum('total'),
                'credit' => Order::whereDate('order_date', $today)->where('payment_method', 'credit')->sum('total'),
            ];
            $data['low_stock'] = Product::where('track_stock', true)
                ->where('stock_quantity', '<=', \App\Models\BusinessSetting::get('low_stock_threshold', 5))
                ->where('is_active', true)->get();
            $data['recent_orders'] = Order::with('cashier', 'employee')
                ->whereDate('order_date', $today)->latest()->take(10)->get();
            $data['recent_rentals'] = RoomRental::with('room', 'receptionist')
                ->latest()->take(10)->get();
        }

        if ($user->isCashier()) {
            $data['my_orders_today'] = Order::where('cashier_id', $user->id)->whereDate('order_date', $today)->count();
            $data['my_sales_today'] = Order::where('cashier_id', $user->id)->whereDate('order_date', $today)->sum('total');
        }

        if ($user->isReceptionist()) {
            $data['todays_checkins'] = RoomRental::whereDate('check_in', $today)->count();
            $data['todays_checkouts'] = RoomRental::whereDate('check_out', $today)->count();
            $data['bed_income_today'] = RoomRental::whereDate('check_in', $today)->sum('total_price');
            $data['occupied_rooms'] = Room::where('status', 'occupied')->with('currentRental')->get();
        }

        if ($user->isEmployee()) {
            $data['my_assigned_orders'] = Order::where('employee_id', $user->id)
                ->whereDate('order_date', $today)->with('items.product')->latest()->get();
        }

        return view('dashboard', compact('data'));
    }
}
