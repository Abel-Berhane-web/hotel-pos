<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('cashier', 'employee', 'items');
        $user = auth()->user();

        // Cashier only sees their own orders
        if ($user->isCashier()) {
            $query->where('cashier_id', $user->id);
        }

        if ($request->filled('date_from')) $query->whereDate('order_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('order_date', '<=', $request->date_to);
        if ($request->filled('payment_method')) $query->where('payment_method', $request->payment_method);
        if ($request->filled('cashier_id')) $query->where('cashier_id', $request->cashier_id);

        $orders = $query->latest()->paginate(20);
        $cashiers = User::whereIn('role', ['admin', 'manager', 'cashier'])->get();

        return view('orders.index', compact('orders', 'cashiers'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $products = Product::where('is_active', true)->with('category')->get();
        $employees = User::where('role', 'employee')->where('is_active', true)->get();

        return view('orders.create', compact('categories', 'products', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,bank_transfer,telebirr,cbe_birr,credit',
            'employee_id' => 'nullable|exists:users,id',
        ]);

        $taxRate = (float) BusinessSetting::get('tax_rate', 0);
        $subtotal = 0;
        $orderItems = [];

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $qty = $item['quantity'];
            $lineTotal = $product->selling_price * $qty;
            $subtotal += $lineTotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'unit_price' => $product->selling_price,
                'line_total' => $lineTotal,
            ];

            // Deduct stock
            if ($product->track_stock) {
                $oldStock = $product->stock_quantity;
                $product->decrement('stock_quantity', $qty);
                $newStock = $product->stock_quantity;
                
                $threshold = BusinessSetting::get('low_stock_threshold', 5);
                if ($oldStock > $threshold && $newStock <= $threshold) {
                    $admins = User::whereIn('role', ['admin', 'manager'])->get();
                    \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\LowStockNotification($product));
                }
            }
        }

        $tax = round($subtotal * $taxRate / 100, 2);
        $total = $subtotal + $tax;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'cashier_id' => auth()->id(),
            'employee_id' => $request->employee_id,
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'note' => $request->note,
            'order_date' => today(),
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        AuditLog::log('order_created', 'Order', $order->id, "Order {$order->order_number} - Total: {$total}");

        return redirect()->route('orders.create')->with('success', __('m.order_placed'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'cashier', 'employee');
        return view('orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        AuditLog::log('order_deleted', 'Order', $order->id, "Order {$order->order_number} voided");

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product->track_stock) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->delete();
        return redirect()->route('orders.index')->with('success', __('m.order_deleted'));
    }
}
