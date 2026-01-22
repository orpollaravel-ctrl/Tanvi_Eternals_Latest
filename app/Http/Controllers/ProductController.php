<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-products')) {
            abort(403,'Permission Denied');
        }
        if ($request->ajax()) {
            $offset = $request->get('offset', 0);
            $limit = 50;
            $search = $request->get('search', '');
            
            $query = Product::query()->latest();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'like', '%' . $search . '%')
                      ->orWhere('barcode_number', 'like', '%' . $search . '%')
                      ->orWhere('tool_code', 'like', '%' . $search . '%');
                });
            }
            
            $total = $query->count();
            $products = $query->skip($offset)->take($limit)->get();
            
            return response()->json([
                'products' => $products,
                'hasMore' => ($offset + $limit) < $total
            ]);
        }

        return view('pages/product', [
            'layout' => 'side-menu',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-products')) {
            abort(403,'Permission Denied');
        }
        $categories = Category::all();
        $units = Unit::all();
		do {
        $barcode = rand(10000000, 99999999);
		} while (\App\Models\Product::where('barcode_number', $barcode)->exists());
        $lastProduct = Product::latest('id')->first(); 
	    $nextToolCode = $lastProduct ? $lastProduct->tool_code + 1 : 1;
        return view('pages/product-create', [
            'layout' => 'side-menu',
            'categories' => $categories,
            'units' => $units,
			'barcode' => $barcode,
			'nextToolCode' => $nextToolCode,
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
            'category_id' => ['nullable', 'exists:categories,id'],
            'product_company' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'minimum_rate' => ['nullable', 'numeric', 'min:0'],
            'maximum_rate' => ['nullable', 'numeric', 'min:0', 'gte:minimum_rate'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_quantity' => ['nullable', 'integer', 'min:0'],
            'unit_id' => ['required', 'exists:units,id'],
            'product_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			'product_type' => ['required', 'in:consumable,repairable'],

        ]);

        $product = Product::create([
            'product_name' => $validated['product_name'],
            'barcode_number' => $validated['barcode_number'] ?? null,
            'tool_code' => $validated['tool_code'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'product_company' => $validated['product_company'] ?? null,
            'hsn_code' => $validated['hsn_code'] ?? null,
            'minimum_rate' => $validated['minimum_rate'] ?? null,
            'maximum_rate' => $validated['maximum_rate'] ?? null,
            'minimum_quantity' => $validated['minimum_quantity'] ?? 0,
            'reorder_quantity' => $validated['reorder_quantity'] ?? 0,
            'unit_id' => $validated['unit_id'],
			'product_type' => $validated['product_type'], // ✅ added here
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
        if (!auth()->check() || !auth()->user()->hasPermission('edit-products')) {
            abort(403,'Permission Denied');
        }
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
            'category_id' => ['nullable', 'exists:categories,id'],
            'product_company' => ['nullable', 'string', 'max:255'],
            'hsn_code' => ['nullable', 'string', 'max:255'],
            'minimum_rate' => ['nullable', 'numeric', 'min:0'],
            'maximum_rate' => ['nullable', 'numeric', 'min:0', 'gte:minimum_rate'],
            'minimum_quantity' => ['nullable', 'integer', 'min:0'],
            'reorder_quantity' => ['nullable', 'integer', 'min:0'],
            'unit_id' => ['required', 'exists:units,id'],
            'product_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
			    'product_type' => ['required', 'in:consumable,repairable'],

        ]);

        $product->update([
            'product_name' => $validated['product_name'],
            'barcode_number' => $validated['barcode_number'] ?? $product->barcode_number,
            'tool_code' => $validated['tool_code'] ?? $product->tool_code,
            'category_id' => $validated['category_id'] ?? null,
            'product_company' => $validated['product_company'] ?? $product->product_company,
            'hsn_code' => $validated['hsn_code'] ?? $product->hsn_code,
            'minimum_rate' => $validated['minimum_rate'] ?? $product->minimum_rate,
            'maximum_rate' => $validated['maximum_rate'] ?? $product->maximum_rate,
            'minimum_quantity' => $validated['minimum_quantity'] ?? $product->minimum_quantity,
            'reorder_quantity' => $validated['reorder_quantity'] ?? $product->reorder_quantity,
            'unit_id' => $validated['unit_id'],
			'product_type' => $validated['product_type'], // ✅ added here
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
        if (!auth()->check() || !auth()->user()->hasPermission('delete-products')) {
            abort(403,'Permission Denied');
        }
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }



    /**
     * Search products for API (used in purchase forms and tool assign)
     */
   public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            // Return initial list of products when query is empty (for dropdown opening)
            $products = Product::orderBy('product_name', 'asc')
                ->limit(50)
                ->get();
        } else {
            // Search products when user types
            $products = Product::where('product_name', 'like', '%' . $query . '%')
                ->orWhere('barcode_number', 'like', '%' . $query . '%')
                ->orWhere('tool_code', 'like', '%' . $query . '%')
                ->orWhere('product_company', 'like', '%' . $query . '%')
                ->orderBy('product_name', 'asc')
                ->limit(50)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $products->map(function ($product) {
                // Calculate remaining quantity
                $purchases = \App\Models\PurchaseItem::where('product_id', $product->id)
                    ->with('purchase')
                    ->get()
                    ->sortBy(function($pi) {
                        return $pi->purchase->bill_date ?? $pi->purchase->created_at;
                    });

                $totalQty = $purchases->sum('quantity') + \App\Models\OpeningStock::where('product_id', $product->id)->sum('quantity');
                $totalValue = $purchases->sum('amount');

                // Total assigned quantity across all tool assigns for this product
                $totalAssigned = \App\Models\ToolAssignItem::where('product_id', $product->id)->sum('quantity');

                // FIFO deduction
                $remainingQty = $totalQty;
                $remainingValue = $totalValue;
                $deducted = 0;
                foreach ($purchases as $pi) {
                    if ($deducted >= $totalAssigned) break;
                    $toDeduct = min($pi->quantity, $totalAssigned - $deducted);
                    $remainingQty -= $toDeduct;
                    $remainingValue -= ($toDeduct / $pi->quantity) * $pi->amount;
                    $deducted += $toDeduct;
                }

                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'barcode_number' => $product->barcode_number,
                    'tool_code' => $product->tool_code,
                    'minimum_rate' => $product->minimum_rate,
                    'minimum_quantity' => $product->minimum_quantity ?? 0,
                    'remaining_quantity' => $remainingQty,
                ];
            })
        ]);
    }

    public function printView(Request $request)
    {
        $search = $request->get('search', '');
        $query = Product::query()->with('category')->latest();
        
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('barcode_number', 'like', '%' . $search . '%')
                    ->orWhere('tool_code', 'like', '%' . $search . '%');
            });
        }
        
        $products = $query->get();
        return view('exports.products-print', compact('products'));
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search', '');
        return Excel::download(new ProductsExport($search), 'products.xlsx');
    }

}
