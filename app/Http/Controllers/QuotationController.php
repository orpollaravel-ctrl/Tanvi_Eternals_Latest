<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Quotation;
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
        $quotations = Quotation::latest()->get();
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
        return view('pages/quotation-create', [
            'layout' => 'side-menu',
            'clients' => $clients, 
        ]);
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'customer_id' => ['required', 'integer','exists:clients,id'],
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
            'salesman' => ['required', 'string', 'max:255'], 
            'barcode' => ['nullable', 'array'], 
        ]);
        
        if (!empty($validated['barcode'])) {
            $validated['barcode'] = implode(',', $validated['barcode']);
        } 
        Quotation::create($validated);

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
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
        return view('pages/quotation-edit', [
            'layout' => 'side-menu',
            'quotation' => $quotation,
            'clients' => $clients, 
            'barcodes' => $barcodes
        ]);
    }

    public function update(Request $request, string $id)
    {
        $quotation = Quotation::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => ['required', 'integer','exists:clients,id'],
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
            'salesman' => ['required', 'string', 'max:255'], 
            'barcode' => ['nullable', 'array'], 
        ]);
          if (!empty($validated['barcode'])) {
            $validated['barcode'] = implode(',', $validated['barcode']);
        } 
        $quotation->update($validated);

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
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
            'customer_id' => 'required|exists:clients,id',
            'customer_code' => 'required',
            'pdf' => 'required|mimes:pdf|max:5120',
        ]);

        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('pdf')->getPathname());
        $text = trim($pdf->getText());
 
        $lines = array_values(array_filter(array_map('trim', explode("\n", $text))));
 
        $headers = array_map('trim', preg_split('/\s{2,}/', strtolower($lines[1])));
 
        for ($i = 2; $i < count($lines); $i++) {

            $row = array_map('trim', preg_split('/\s{2,}/', $lines[$i]));

            if (count($row) !== count($headers)) {
                continue;
            }

            $data = array_combine($headers, $row);
 
            if (count($data) === 1) {
                $headerLine = array_key_first($data);
                $valueLine  = $data[$headerLine];

                $headersFix = array_map('trim', explode("\t", strtolower($headerLine)));
                $valuesFix  = array_map('trim', explode("\t", $valueLine));

                if (count($headersFix) === count($valuesFix)) {
                    $data = array_combine($headersFix, $valuesFix);
                }
            }  

            Quotation::create([
                'customer_id' => $request->customer_id,
                'customer_code' => $request->customer_code,
                'contact' => $request->contact,
                'metal' => $data['metal'] ?? null,
                'purity' => $data['purity'] ?? null,
                'diamond' => $data['diamond'] ?? null,
                'women_ring_size_from' => $data['women_ring_size_from'] ?? null,
                'women_ring_size_to' => $data['women_ring_size_to'] ?? null,
                'men_ring_size_from' => $data['men_ring_size_from'] ?? null,
                'men_ring_size_to' => $data['men_ring_size_to'] ?? null,
                'remarks' => $data['remarks'] ?? null,
            ]);
        }

        return back()->with('success', 'Quotation imported successfully.');
    } 

    public function show(Quotation $quotation)
    {
        return view('pages/quotation-show', compact('quotation'));
    }
}