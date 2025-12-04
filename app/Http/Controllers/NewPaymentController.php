<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payment = Payment::query()->latest()->paginate(10);
        return view('pages.payment', [
            'layout' => 'side-menu',
            'payment' => $payment,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextSerialNo = (Payment::max('serial_no') ?? 0) + 1;

        return view('pages.payment-create', compact('nextSerialNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        Payment::create($request->validated());

        return redirect()->route('payment')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $payment = Payment::findOrFail($id);
        return view('pages.payment-edit', [
            'layout' => 'side-menu',
            'payment' => $payment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($request->validated());

        return redirect()->route('payment')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payment')->with('success', 'Payment deleted successfully.');
    }
}
