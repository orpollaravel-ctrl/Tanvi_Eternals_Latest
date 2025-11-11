<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseParty;
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
        $purchases = Purchase::with('purchaseParty')->latest()->paginate(10);
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
        $purchaseParties = PurchaseParty::all();
        return view('pages/purchase-create', [
            'layout' => 'side-menu',
            'purchaseParties' => $purchaseParties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_party_id' => ['required', 'exists:purchase_parties,id'],
            'bill_date' => ['required', 'date'],
            'bill_number' => ['required', 'string', 'max:100', 'unique:purchases,bill_number'],
            'delivery_date' => ['required', 'date'],
            'total_invoice_amount' => ['required', 'numeric', 'min:0'],
            'bill_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase = Purchase::create([
            'purchase_party_id' => $validated['purchase_party_id'],
            'bill_date' => $validated['bill_date'],
            'bill_number' => $validated['bill_number'],
            'delivery_date' => $validated['delivery_date'],
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
                'expiry_date' => $item['expiry_date'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $purchase = Purchase::with('items')->findOrFail($id);
        $purchaseParties = PurchaseParty::all();
        return view('pages/purchase-edit', [
            'layout' => 'side-menu',
            'purchase' => $purchase,
            'purchaseParties' => $purchaseParties,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        $validated = $request->validate([
            'purchase_party_id' => ['required', 'exists:purchase_parties,id'],
            'bill_date' => ['required', 'date'],
            'bill_number' => ['required', 'string', 'max:100', 'unique:purchases,bill_number,' . $purchase->id],
            'delivery_date' => ['required', 'date'],
            'total_invoice_amount' => ['required', 'numeric', 'min:0'],
            'bill_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.expiry_date' => ['nullable', 'date'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
        ]);

        $purchase->update([
            'purchase_party_id' => $validated['purchase_party_id'],
            'bill_date' => $validated['bill_date'],
            'bill_number' => $validated['bill_number'],
            'delivery_date' => $validated['delivery_date'],
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
                'expiry_date' => $item['expiry_date'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'amount' => $item['amount'],
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
