<?php

namespace App\Http\Controllers;

use App\Models\PurchaseParty;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchasePartyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $purchaseParties = PurchaseParty::query()->latest()->paginate(10);
        return view('pages/purchase-party', [
            'layout' => 'side-menu',
            'purchaseParties' => $purchaseParties,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages/purchase-party-create', [
            'layout' => 'side-menu',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'party_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'gst_number' => ['required', 'string', 'max:15', 'unique:purchase_parties,gst_number'],
            'address' => ['required', 'string'],
            'bank_account_number' => ['required', 'string', 'max:20'],
            'ifsc_code' => ['required', 'string', 'max:11'],
            'mobile_number' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'unique:purchase_parties,email'],
        ]);

        PurchaseParty::create($validated);

        return redirect()->route('purchase-parties.index')->with('success', 'Purchase party created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $purchaseParty = PurchaseParty::findOrFail($id);
        return view('pages/purchase-party-edit', [
            'layout' => 'side-menu',
            'purchaseParty' => $purchaseParty,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $purchaseParty = PurchaseParty::findOrFail($id);

        $validated = $request->validate([
            'party_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'gst_number' => ['required', 'string', 'max:15', 'unique:purchase_parties,gst_number,' . $purchaseParty->id],
            'address' => ['required', 'string'],
            'bank_account_number' => ['required', 'string', 'max:20'],
            'ifsc_code' => ['required', 'string', 'max:11'],
            'mobile_number' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'unique:purchase_parties,email,' . $purchaseParty->id],
        ]);

        $purchaseParty->update($validated);

        return redirect()->route('purchase-parties.index')->with('success', 'Purchase party updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseParty = PurchaseParty::findOrFail($id);
        $purchaseParty->delete();

        return redirect()->route('purchase-parties.index')->with('success', 'Purchase party deleted successfully.');
    }
}
