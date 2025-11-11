<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRateFixRequest;
use App\Models\ClietRateFix;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClietRateFixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clientRates = ClietRateFix::query()->latest()->paginate(10);
        return view('pages.client-rate', [
            'layout' => 'side-menu',
            'clientRates' => $clientRates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextSerialNo = (ClietRateFix::max('serial_no') ?? 0) + 1;

        return view('pages.client-rate-create', compact('nextSerialNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRateFixRequest $request)
    {
        ClietRateFix::create($request->validated());

        return redirect()->route('client-rate-fix')->with('success', 'Client rate fix created successfully.');
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
        $clientRate = ClietRateFix::findOrFail($id);
        return view('pages.client-rate-edit', [
            'layout' => 'side-menu',
            'clientRate' => $clientRate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRateFixRequest $request, $id)
    {
        $clientRate = ClietRateFix::findOrFail($id);
        $clientRate->update($request->validated());

        return redirect()->route('client-rate-fix')->with('success', 'Client rate fix updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clientRate = ClietRateFix::findOrFail($id);
        $clientRate->delete();

        return redirect()->route('client-rate-fix')->with('success', 'Client rate fix deleted successfully.');
    }
}
