<?php

namespace App\Http\Controllers;

use App\Models\ClientRateCutPending;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ClientRateCutPendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $clientRateCutPendings = ClientRateCutPending::query()->latest()->paginate(10);
        return view('pages.client-rate-cut-pending', [
            'layout' => 'side-menu',
            'clientRateCutPendings' => $clientRateCutPendings,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.client-rate-cut-pending-create', [
            'layout' => 'side-menu'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_no' => ['required', 'string', 'max:255'],
            'client_code' => ['nullable', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'sales_person' => ['required', 'string', 'max:255'],
            'pure_weight' => ['required', 'numeric', 'min:0'],
            'sale_rate' => ['required', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'rate_cut' => ['nullable', 'numeric', 'min:0'],
            'amt' => ['nullable', 'numeric', 'min:0'],
            'diff_amt' => ['nullable', 'numeric'],
            'transaction_no' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'],
        ]);

        // Calculate amount if not provided
        if (!isset($validated['amount']) && isset($validated['pure_weight']) && isset($validated['sale_rate'])) {
            $validated['amount'] = $validated['pure_weight'] * $validated['sale_rate'];
        }

        // Calculate diff_amt if rate_cut and amt are provided
        if (isset($validated['rate_cut']) && isset($validated['amt'])) {
            $validated['diff_amt'] = $validated['amt'] - ($validated['amount'] ?? 0);
        }
        ClientRateCutPending::create($validated);

        return redirect()->route('client-rate-cut-pending')->with('success', 'Client Rate Cut Pending created successfully.');
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
        $clientRateCutPending = ClientRateCutPending::findOrFail($id);
        return view('pages.client-rate-cut-pending-edit', [
            'layout' => 'side-menu',
            'clientRateCutPending' => $clientRateCutPending,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $clientRateCutPending = ClientRateCutPending::findOrFail($id);

        $validated = $request->validate([
            'invoice_no' => ['required', 'string', 'max:255'],
            'client_code' => ['nullable', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'sales_person' => ['required', 'string', 'max:255'],
            'pure_weight' => ['required', 'numeric', 'min:0'],
            'sale_rate' => ['required', 'numeric', 'min:0'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'rate_cut' => ['nullable', 'numeric', 'min:0'],
            'amt' => ['nullable', 'numeric', 'min:0'],
            'diff_amt' => ['nullable', 'numeric'],
            'transaction_no' => ['nullable', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'],
        ]);

        // Calculate amount if not provided
        if (!isset($validated['amount']) && isset($validated['pure_weight']) && isset($validated['sale_rate'])) {
            $validated['amount'] = $validated['pure_weight'] * $validated['sale_rate'];
        }

        // Calculate diff_amt if rate_cut and amt are provided
        if (isset($validated['rate_cut']) && isset($validated['amt'])) {
            $validated['diff_amt'] = $validated['amt'] - ($validated['amount'] ?? 0);
        }

        $clientRateCutPending->update($validated);

        return redirect()->route('client-rate-cut-pending')->with('success', 'Client Rate Cut Pending updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $clientRateCutPending = ClientRateCutPending::findOrFail($id);

        // Delete associated file if exists
        if ($clientRateCutPending->file_path) {
            Storage::disk('public')->delete($clientRateCutPending->file_path);
        }

        $clientRateCutPending->delete();

        return redirect()->route('client-rate-cut-pending')->with('success', 'Client Rate Cut Pending deleted successfully.');
    }
}
