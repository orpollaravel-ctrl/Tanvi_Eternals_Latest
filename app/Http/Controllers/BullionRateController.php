<?php

namespace App\Http\Controllers;

use App\Http\Requests\BullionRateRequest;
use App\Models\BullionRate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BullionRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $bullionRates = BullionRate::query()->latest()->paginate(10);
        return view('pages.bullion-rate', [
            'layout' => 'side-menu',
            'bullionRates' => $bullionRates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nextSerialNo = (BullionRate::max('serial_no') ?? 0) + 1;

        return view('pages.bullion-rate-create', compact('nextSerialNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BullionRateRequest $request)
    {
        BullionRate::create($request->validated());

        return redirect()->route('bullion-rate')->with('success', 'Bullion rate created successfully.');
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
        $bullionRate = BullionRate::findOrFail($id);
        return view('pages.bullion-rate-edit', [
            'layout' => 'side-menu',
            'bullionRate' => $bullionRate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BullionRateRequest $request, string $id)
    {
        $bullionRate = BullionRate::findOrFail($id);
        $bullionRate->update($request->validated());

        return redirect()->route('bullion-rate')->with('success', 'Bullion rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bullionRate = BullionRate::findOrFail($id);
        $bullionRate->delete();

        return redirect()->route('bullion-rate')->with('success', 'Bullion rate deleted successfully.');
    }
}
