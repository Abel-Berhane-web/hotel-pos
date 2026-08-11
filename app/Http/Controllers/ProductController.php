<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('name_en')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_am' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'track_stock' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['track_stock'] = $request->boolean('track_stock');
        $data['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($data);
        AuditLog::log('product_created', 'Product', $product->id, $product->name_en);

        return redirect()->route('products.index')->with('success', __('m.success'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_am' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'selling_price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
            'track_stock' => 'boolean',
            'stock_quantity' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['track_stock'] = $request->boolean('track_stock');
        $data['is_active'] = $request->boolean('is_active', true);

        $product->update($data);
        AuditLog::log('product_updated', 'Product', $product->id, $product->name_en);

        return redirect()->route('products.index')->with('success', __('m.success'));
    }

    public function destroy(Product $product)
    {
        $product->update(['is_active' => false]);
        AuditLog::log('product_deactivated', 'Product', $product->id, $product->name_en);
        return redirect()->route('products.index')->with('success', __('m.success'));
    }
}
