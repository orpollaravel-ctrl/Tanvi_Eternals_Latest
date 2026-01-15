<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-tool-purchases')) {
            abort(403,'Permission Denied');
        }
        $purchases = Purchase::with('vendor')->orderByDesc('created_at')->get();
        return view('pages/purchase', [
            'layout' => 'side-menu',
            'purchases' => $purchases,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-tool-purchases')) {
            abort(403,'Permission Denied');
        }
        $vendors = Vendor::all();
        $products = \App\Models\Product::all();
        return view('pages/purchase-create', [
            'layout' => 'side-menu',
            'vendors' => $vendors,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'bill_date' => ['required', 'date'],
            'bill_number' => ['required', 'string', 'max:100', 'unique:purchases,bill_number'],
            'delivery_date' => ['nullable', 'date'],
            'total_invoice_amount' => ['required', 'numeric', 'min:0'],
            'bill_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.serial_number' => ['nullable', 'string', 'max:255'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
			 'items.*.gst_percentage' => ['nullable', 'numeric', 'in:0,3,5,12,18,28'],
            'items.*.gst_value' => ['nullable', 'numeric', 'min:0'],
            'items.*.final_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $purchase = Purchase::create([
            'vendor_id' => $validated['vendor_id'],
            'bill_date' => $validated['bill_date'],
            'bill_number' => $validated['bill_number'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'total_invoice_amount' => $validated['total_invoice_amount'],
        ]);

        if ($request->hasFile('bill_photo')) {
            $file = $request->file('bill_photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'media/purchase/' . $purchase->id;
            $file->move(public_path($filePath), $fileName);
            $purchase->update(['bill_photo' => $fileName]);
        }

        // Create purchase items
        foreach ($validated['items'] as $item) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_name' => $item['product_name'],
                'product_id' => $item['product_id'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
				   'gst_percentage' => $item['gst_percentage'] ?? null,
                'gst_value' => $item['gst_value'] ?? null,
                'final_amount' => $item['final_amount'] ?? null,
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(string $id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-tool-purchases')) {
            abort(403,'Permission Denied');
        }
        $purchase = Purchase::with('items')->findOrFail($id);
        $vendors = Vendor::all();
        $products = \App\Models\Product::all();
        return view('pages/purchase-edit', [
            'layout' => 'side-menu',
            'purchase' => $purchase,
            'vendors' => $vendors,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'bill_date' => ['required', 'date'],
            'bill_number' => ['required', 'string', 'max:100', 'unique:purchases,bill_number,' . $purchase->id],
            'delivery_date' => ['nullable', 'date'],
            'total_invoice_amount' => ['required', 'numeric', 'min:0'],
            'bill_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.serial_number' => ['nullable', 'string', 'max:255'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
			'items.*.gst_percentage' => ['nullable', 'numeric', 'in:0,3,5,12,18,28'],
            'items.*.gst_value' => ['nullable', 'numeric', 'min:0'],
            'items.*.final_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $purchase->update([
            'vendor_id' => $validated['vendor_id'],
            'bill_date' => $validated['bill_date'],
            'bill_number' => $validated['bill_number'],
            'delivery_date' => $validated['delivery_date'] ?? null,
            'total_invoice_amount' => $validated['total_invoice_amount'],
        ]);

        if ($request->hasFile('bill_photo')) {
            $file = $request->file('bill_photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'media/purchase/' . $purchase->id;
            $file->move(public_path($filePath), $fileName);
            $purchase->update(['bill_photo' => $fileName]);
        }

        // Delete existing items and create new ones
        $purchase->items()->delete();
        foreach ($validated['items'] as $item) {
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_name' => $item['product_name'],
                'product_id' => $item['product_id'] ?? null,
                'serial_number' => $item['serial_number'] ?? null,
                'expiry_date' => $item['expiry_date'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
				'gst_percentage' => $item['gst_percentage'] ?? null,
                'gst_value' => $item['gst_value'] ?? null,
                'final_amount' => $item['final_amount'] ?? null,
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-tool-purchases')) {
            abort(403,'Permission Denied');
        }
        try {
            $purchase = Purchase::findOrFail($id);

            // Delete associated purchase items first
            $purchase->items()->delete();

            $purchase->delete();

            return redirect()->route('purchases.index')->with('success', 'Purchase and associated items deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('purchases.index')->with('error', 'Unable to delete purchase. It may be referenced by other records.');
        }
    }
}
