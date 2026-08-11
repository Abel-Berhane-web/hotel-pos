<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:purchase,adjustment,damage,return',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $qty = $request->quantity;
        if (in_array($request->type, ['damage', 'return'])) {
            $qty = -$qty;
        }

        StockAdjustment::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $qty,
            'reason' => $request->reason,
        ]);

        $product->increment('stock_quantity', $qty);
        AuditLog::log('stock_adjusted', 'Product', $product->id, "{$request->type}: {$qty} - {$request->reason}");

        return back()->with('success', __('m.stock_adjusted'));
    }
}
