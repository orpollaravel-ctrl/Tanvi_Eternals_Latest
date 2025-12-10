<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotationController extends Controller
{
    public function index(): View   
    {
        $quotations = Quotation::latest()->get();
        return view('pages/quotation', [
            'layout' => 'side-menu',
            'quotations' => $quotations,
        ]);
    }

    public function create(): View
    {
        $clients = \App\Models\Client::orderBy('name')->get();
        return view('pages/quotation-create', [
            'layout' => 'side-menu',
            'clients' => $clients,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'customer_code' => ['required', 'string', 'max:255'],
            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI'],
            'women_ring_size_from' => ['nullable', 'string', 'max:255'],
            'women_ring_size_to' => ['nullable', 'string', 'max:255'],
            'men_ring_size_from' => ['nullable', 'string', 'max:255'],
            'men_ring_size_to' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        Quotation::create($validated);

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function edit(string $id): View
    {
        $quotation = Quotation::findOrFail($id);
        $clients = \App\Models\Client::orderBy('name')->get();
        return view('pages/quotation-edit', [
            'layout' => 'side-menu',
            'quotation' => $quotation,
            'clients' => $clients,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'customer_code' => ['required', 'string', 'max:255'],
            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI'],
            'women_ring_size_from' => ['nullable', 'string', 'max:255'],
            'women_ring_size_to' => ['nullable', 'string', 'max:255'],
            'men_ring_size_from' => ['nullable', 'string', 'max:255'],
            'men_ring_size_to' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        $quotation->update($validated);

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }

    public function destroy(string $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function print(): View
    {
        $quotations = Quotation::latest()->get();
        return view('pages/quotation-print', [
            'quotations' => $quotations,
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        $quotations = Quotation::latest()->get();
        
        $filename = 'quotations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($quotations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer Name', 'Contact', 'Customer Code', 'Metal', 'Purity', 'Diamond', 'Women Ring Size From', 'Women Ring Size To', 'Men Ring Size From', 'Men Ring Size To', 'Remarks']);
            
            foreach ($quotations as $quotation) {
                fputcsv($file, [
                    $quotation->customer_name,
                    $quotation->contact,
                    $quotation->customer_code,
                    ucfirst($quotation->metal),
                    $quotation->purity,
                    $quotation->diamond,
                    $quotation->women_ring_size_from,
                    $quotation->women_ring_size_to,
                    $quotation->men_ring_size_from,
                    $quotation->men_ring_size_to,
                    $quotation->remarks,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}