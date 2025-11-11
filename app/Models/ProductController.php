<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::query()->latest()->paginate(10);
        return view('pages/product', [
            'layout' => 'side-menu',
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('pages/product-create', [
            'layout' => 'side-menu',
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'barcode_number' => ['nullable', 'string', 'max:255', 'unique:products,barcode_number'],
            'tool_code' => ['nullable', 'string', 'max:255', 'unique:products,tool_code'],
            'category_id' => ['required', 'exists:categories,id'],
            // 'product_company' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'minimum_rate' => ['nullable', 'numeric', 'min:0'],
            'maximum_rate' => ['nullable', 'numeric', 'min:0'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_quantity' => ['nullable', 'integer', 'min:0'],
            'unit_id' => ['required', 'exists:units,id'],
            'product_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $product = Product::create([
            'product_name' => $validated['product_name'],
            'barcode_number' => $validated['barcode_number'] ?? null,
            'tool_code' => $validated['tool_code'] ?? null,
            'category_id' => $validated['category_id'],
            // 'product_company' => $validated['product_company'] ?? null,
            'hsn_code' => $validated['hsn_code'] ?? null,
            'minimum_rate' => $validated['minimum_rate'] ?? null,
            'maximum_rate' => $validated['maximum_rate'] ?? null,
            'minimum_quantity' => $validated['minimum_quantity'] ?? 0,
            'reorder_quantity' => $validated['reorder_quantity'] ?? 0,
            'unit_id' => $validated['unit_id'],
        ]);

        if ($request->hasFile('product_photo')) {
            $file = $request->file('product_photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'media/product/' . $product->id;
            $file->move(public_path($filePath), $fileName);
            $product->update(['product_photo' => $fileName]);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $product = Product::findOrFail($id);
        return view('pages/product-show', [
            'layout' => 'side-menu',
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $units = Unit::all();
        return view('pages/product-edit', [
            'layout' => 'side-menu',
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'barcode_number' => ['nullable', 'string', 'max:255', 'unique:products,barcode_number,' . $product->id],
            'tool_code' => ['nullable', 'string', 'max:255', 'unique:products,tool_code,' . $product->id],
            'category_id' => ['required', 'exists:categories,id'],
            // 'product_company' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'minimum_rate' => ['nullable', 'numeric', 'min:0'],
            'maximum_rate' => ['nullable', 'numeric', 'min:0'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_quantity' => ['nullable', 'integer', 'min:0'],
            'unit_id' => ['required', 'exists:units,id'],
            'product_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $product->update([
            'product_name' => $validated['product_name'],
            'barcode_number' => $validated['barcode_number'] ?? $product->barcode_number,
            'tool_code' => $validated['tool_code'] ?? $product->tool_code,
            'category_id' => $validated['category_id'],
            // 'product_company' => $validated['product_company'] ?? $product->product_company,
            'hsn_code' => $validated['hsn_code'] ?? $product->hsn_code,
            'minimum_rate' => $validated['minimum_rate'] ?? $product->minimum_rate,
            'maximum_rate' => $validated['maximum_rate'] ?? $product->maximum_rate,
            'minimum_quantity' => $validated['minimum_quantity'] ?? $product->minimum_quantity,
            'reorder_quantity' => $validated['reorder_quantity'] ?? $product->reorder_quantity,
            'unit_id' => $validated['unit_id'],
        ]);

        if ($request->hasFile('product_photo')) {
            $file = $request->file('product_photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'media/product/' . $product->id;
            $file->move(public_path($filePath), $fileName);
            $product->update(['product_photo' => $fileName]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
