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
        $quotations = Quotation::where('customer_id', $customer->id)
            ->where(function ($q) {
                $q->whereNull('barcode')
                ->orWhere('barcode', '');
            })
            ->latest()
            ->get();

        return view('customer.quotations.index', compact('quotations'));
    }

    public function show(Quotation $quotation)
    {
        $customer = auth('client')->user();
        $quotation->load('pdfs');
        if (!$customer || !$customer->hasPermission('view-customer-quotations')) {
            abort(401, 'Unauthenticated');
        }  

        return view('customer.quotations.show', compact('quotation'));
    }
}
