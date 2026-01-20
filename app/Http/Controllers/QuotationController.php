<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationPdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Smalot\PdfParser\Parser;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotationController extends Controller
{
    public function index(): View   
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-quotations')) {
            abort(403,'Permission Denied');
        }
        $quotations = Quotation::with(['client' => function($query) {
            $query->where('quotation_no', '!=', null);
        }])->latest()->get();
        
        // Add client PDF data to quotations
        foreach ($quotations as $quotation) {
            $client = \App\Models\Client::where('quotation_no', $quotation->id)->first();
            $quotation->client_pdf = $client ? $client->quotation_pdf : null;
        }
        
        return view('pages/quotation', [
            'layout' => 'side-menu',
            'quotations' => $quotations,
        ]);
    }

    public function create(): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('create-quotations')) {
            abort(403,'Permission Denied');
        }
        $clients = \App\Models\Client::orderBy('name')->get();
        $salesmen = Employee::whereHas('department', function ($q) {
            $q->where('name', 'sales');
        })
        ->orderBy('name')
        ->get();
 
        return view('pages/quotation-create', [
            'layout' => 'side-menu',
            'clients' => $clients,  
            'salesmen' => $salesmen,
        ]);
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'salesman_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],

            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI,CVD'],

            'women_ring_size_from' => ['nullable'],
            'women_ring_size_to' => ['nullable'],
            'men_ring_size_from' => ['nullable'],
            'men_ring_size_to' => ['nullable'],

            'remarks' => ['nullable', 'string'],
            'barcode' => ['nullable', 'array'],
        ]);

        $validated['barcode'] = !empty($validated['barcode'])
            ? implode(',', $validated['barcode'])
            : null;

        Quotation::create($validated);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation created successfully.');
    }

    public function edit(string $id): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('edit-quotations')) {
            abort(403,'Permission Denied');
        }
        $quotation = Quotation::findOrFail($id);
        $clients = \App\Models\Client::orderBy('name')->get(); 
        $barcodes = [];
        if (!empty($quotation->barcode)) {
            $barcodes = explode(',', $quotation->barcode);
        }

        $salesmen = Employee::whereHas('department', function ($q) {
            $q->where('name', 'sales');
        })
        ->orderBy('name')
        ->get();
        return view('pages/quotation-edit', [
            'layout' => 'side-menu',
            'quotation' => $quotation,
            'clients' => $clients, 
            'barcodes' => $barcodes,
            'salesmen' => $salesmen
        ]);
    }

    public function update(Request $request, string $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'salesman_name' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],

            'metal' => ['required', 'in:yellow gold,rose gold,white gold'],
            'purity' => ['required', 'in:22k,18k,14k,9k'],
            'diamond' => ['required', 'in:SI-IJ,SI-GH,VS-GH,VVS-EF,VS-SIGH,VS-ISHI,SI-HI,CVD'],

            'women_ring_size_from' => ['nullable'],
            'women_ring_size_to' => ['nullable'],
            'men_ring_size_from' => ['nullable'],
            'men_ring_size_to' => ['nullable'],

            'remarks' => ['nullable', 'string'],
            'barcode' => ['nullable', 'array'],
        ]);

        $validated['barcode'] = !empty($validated['barcode'])
            ? implode(',', $validated['barcode'])
            : null;

        $quotation->update($validated);

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation updated successfully.');
    }

    public function destroy(string $id)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('delete-quotations')) {
            abort(403,'Permission Denied');
        }
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
                    $quotation->customer_id,
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

    public function importPdf(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
            'pdf.*' => 'required|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('pdf')) {
            foreach ($request->file('pdf') as $file) {
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(
                    public_path('uploads/quotation_pdfs'),
                    $fileName
                );

                QuotationPdf::create([
                    'quotation_id' => $request->quotation_id,
                    'file_path' => 'uploads/quotation_pdfs/' . $fileName,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'PDF uploaded successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('pdfs');
        
        // Get client PDF for this quotation
        $client = \App\Models\Client::where('quotation_no', $quotation->id)->first();
        $quotation->client_pdf = $client ? $client->quotation_pdf : null;
        
        return view('pages/quotation-show', [
            'layout' => 'side-menu',
            'quotation' => $quotation
        ]);    
    }
}