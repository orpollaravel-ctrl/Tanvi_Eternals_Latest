<?php

namespace App\Http\Controllers;

use App\Models\OpeningStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class OpeningStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $perPage = 25;
            $search = $request->get('search', '');

            $query = Product::leftJoin('opening_stocks', 'products.id', '=', 'opening_stocks.product_id')
                ->select('products.*', 'opening_stocks.quantity', 'opening_stocks.mrp', 'opening_stocks.sale_rate', 'opening_stocks.purchase_price')
                ->orderBy('products.product_name');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('products.product_name', 'like', '%' . $search . '%')
                        ->orWhere('products.barcode_number', 'like', '%' . $search . '%')
                        ->orWhere('products.tool_code', 'like', '%' . $search . '%');
                });
            }

            $products = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'has_more' => $products->hasMorePages(),
            ]);
        }

        // Initial load with first 25 records
        $products = Product::leftJoin('opening_stocks', 'products.id', '=', 'opening_stocks.product_id')
            ->select('products.*', 'opening_stocks.quantity', 'opening_stocks.mrp', 'opening_stocks.sale_rate', 'opening_stocks.purchase_price')
            ->orderBy('products.product_name')
            ->paginate(25);

        return view('pages/opening-stock', [
            'layout' => 'side-menu',
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::whereDoesntHave('openingStock')->get();
        return view('pages/opening-stock-create', [
            'layout' => 'side-menu',
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id', 'unique:opening_stocks,product_id'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'sale_rate' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        OpeningStock::create($validated);

        return redirect()->route('opening-stock.index')->with('success', 'Opening stock created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $openingStock = OpeningStock::with('product')->findOrFail($id);
        return view('pages/opening-stock-show', [
            'layout' => 'side-menu',
            'openingStock' => $openingStock,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $openingStock = OpeningStock::findOrFail($id);
        return view('pages/opening-stock-edit', [
            'layout' => 'side-menu',
            'openingStock' => $openingStock,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $openingStock = OpeningStock::findOrFail($id);

        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'min:0'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'sale_rate' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $openingStock->update($validated);

        return redirect()->route('opening-stock.index')->with('success', 'Opening stock updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $openingStock = OpeningStock::findOrFail($id);
        $openingStock->delete();

        return redirect()->route('opening-stock.index')->with('success', 'Opening stock deleted successfully.');
    }

    /**
     * Update opening stock via AJAX
     */
    public function updateAjax(Request $request, string $productId)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'sale_rate' => ['nullable', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Convert empty strings to appropriate null/zero values
        $data = [];
        if (array_key_exists('quantity', $validated)) {
            $data['quantity'] = $validated['quantity'] === '' ? 0 : $validated['quantity'];
        }
        if (array_key_exists('mrp', $validated)) {
            $data['mrp'] = $validated['mrp'] === '' ? null : $validated['mrp'];
        }
        if (array_key_exists('sale_rate', $validated)) {
            $data['sale_rate'] = $validated['sale_rate'] === '' ? null : $validated['sale_rate'];
        }
        if (array_key_exists('purchase_price', $validated)) {
            $data['purchase_price'] = $validated['purchase_price'] === '' ? null : $validated['purchase_price'];
        }

        $openingStock = OpeningStock::where('product_id', $productId)->first();

        if ($openingStock) {
            // Update existing record
            $openingStock->update($data);
        } else {
            // Create new record if any field has value
            if (!empty($data)) {
                $data['product_id'] = $productId;
                OpeningStock::create($data);
            }
        }

        return response()->json(['success' => true]);
    }
}
