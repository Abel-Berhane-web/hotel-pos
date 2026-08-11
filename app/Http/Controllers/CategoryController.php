<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.form', ['category' => new Category()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_am' => 'required|string|max:255',
            'type' => 'required|in:drink,food',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        Category::create($data);
        return redirect()->route('categories.index')->with('success', __('m.success'));
    }

    public function edit(Category $category)
    {
        return view('categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_am' => 'required|string|max:255',
            'type' => 'required|in:drink,food',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $category->update($data);
        return redirect()->route('categories.index')->with('success', __('m.success'));
    }

    public function destroy(Category $category)
    {
        $category->update(['is_active' => false]);
        return redirect()->route('categories.index')->with('success', __('m.success'));
    }
}
