<?php

namespace App\Http\Controllers;

use App\Http\Requests\BullionPurchaseRequest;
use App\Models\BullionPurchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BullionPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bullionPurchases = BullionPurchase::query()->latest()->paginate(10);
        return view('pages.bullion-purchase', [
            'layout' => 'side-menu',
            'bullionPurchases' => $bullionPurchases,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nextSerialNo = (BullionPurchase::max('serial_no') ?? 0) + 1;

        return view('pages.bullion-purchase-create', compact('nextSerialNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BullionPurchaseRequest $request)
    {
        $validated = $request->validated();
        $validated['serial_no'] = BullionPurchase::max('id') + 1;

        $validated['amount'] = round(
            ($validated['converted_weight'] ?? 0) * ($validated['purchase_rate'] ?? 0),
            2
        );

        BullionPurchase::create($validated);

        return redirect()
            ->route('bullion-purchase')
            ->with('success', 'Bullion purchase created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $bullionPurchase = BullionPurchase::findOrFail($id);
        
        return view('pages.bullion-purchase-edit', [
            'layout' => 'side-menu',
            'bullionPurchase' => $bullionPurchase,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BullionPurchaseRequest $request, $id)
    {
        $bullionPurchase = BullionPurchase::findOrFail($id);
        $validated = $request->validated();

        $validated['amount'] = round(
            ($validated['converted_weight'] ?? 0) * ($validated['purchase_rate'] ?? 0),
            2
        );

        $bullionPurchase->update($validated);

        return redirect()
            ->route('bullion-purchase')
            ->with('success', 'Bullion purchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bullionPurchase = BullionPurchase::findOrFail($id);
        $bullionPurchase->delete();

        return redirect()->route('bullion-purchase')->with('success', 'Bullion purchase deleted successfully.');
    }
}
