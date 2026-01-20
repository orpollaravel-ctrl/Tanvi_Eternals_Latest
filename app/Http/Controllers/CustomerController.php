<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = auth('client')->user(); 

        if (!$customer || !$customer->hasPermission('view-customer-quotations')) {
            abort(403, 'Permission Denied');
        }
         
        $quotations = collect();
        if ($customer->quotation_no) {
            $quotation = Quotation::find($customer->quotation_no);
            if ($quotation) { 
                $quotation->client_pdf = $customer->quotation_pdf;
                $quotations = collect([$quotation]);
            }
        }

        return view('customer.quotations.index', [
            'layout' => 'side-menu',
            'quotations' => $quotations
        ]);
    }

    public function show(Quotation $quotation)
    {
        $customer = auth('client')->user();
        
        if (!$customer || !$customer->hasPermission('view-customer-quotations')) {
            abort(401, 'Unauthenticated');
        }
        
        $quotation->load('pdfs');
        
        // Add client PDF data
        $quotation->client_pdf = $customer->quotation_pdf;

        return view('customer.quotations.show', [
            'layout' => 'side-menu',
            'quotation' => $quotation
        ]);
    } 
}
