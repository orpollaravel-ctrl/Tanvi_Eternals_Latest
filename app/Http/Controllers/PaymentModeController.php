<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentModeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-payment-modes')) {
            abort(403,'Permission Denied');
        }
        $paymentmodes=PaymentMode::withCount('payments')->latest()->paginate(10);
        return view('paymentmode.index',compact('paymentmodes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!auth()->check() || !auth()->user()->hasPermission('create-payment-modes')) {
            abort(403,'Permission Denied');
        }
        return view('paymentmode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=['name'=>'required|string|max:255|unique:payment_modes'];
        $this->validate($request,$rules);
        PaymentMode::create($request->all());
        Session::flash('success_message', "Payment Mode added successfully.");
        return redirect()->route('paymentmodes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMode  $paymentMode
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMode $paymentMode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMode  $paymentmode
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMode $paymentmode)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('edit-payment-modes')) {
            abort(403,'Permission Denied');
        }
        // dd($paymentmode);
        return view('paymentmode.edit',compact('paymentmode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMode  $paymentMode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMode $paymentmode)
    {        
        $rules=['name'=>"required|string|max:255|unique:payment_modes,name,".$paymentmode->id.",id",];
        $this->validate($request,$rules);
        $paymentmode->update($request->all());
        Session::flash('success_message', "Payment Mode updated successfully.");
        return redirect()->route('paymentmodes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMode  $paymentMode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMode $paymentMode, $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-payment-modes')) {
            abort(403,'Permission Denied');
        }
        $paymentMode = PaymentMode::findOrFail($id);
        $paymentMode->delete();

        return redirect()->route('paymentmodes.index')->with('success', 'PaymentMode deleted successfully.');   
    }
}
