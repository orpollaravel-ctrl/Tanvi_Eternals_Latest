<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Bullion;
use App\Models\PaymentMode;
use App\Models\Transaction;
use App\Models\User;

class PaymentController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-payment')) {
                return abort(403);
            }
            return $next($request);
        });
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-payments')) {
           abort(403,'Permission Denied');
        }
        $bullions = Bullion::where('status', 1)->get();
        $payments = Payment::with('bullion', 'paymentMode', 'transferredBy', 'createdBy', 'updatedBy')
            ->when($request->get('bullion'), function ($q) use ($request) {
                return $q->where('bullion_id', $request->get('bullion'));
            })->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
                return $q->whereBetween('pay_date', [$request->get('from_date'), $request->get('to_date')]);
            })->latest()->paginate(10);
        return view('payment.index', compact('payments', 'bullions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-payments')) {
           abort(403,'Permission Denied');
        }
        // DB::enableQueryLog();
        // $transactions = Transaction::query()->leftJoin('payment_transaction', 'transactions.id', 'payment_transaction.transaction_id')
        //     ->leftJoin('bullion_rate_fixes', 'bullion_rate_fixes.id', 'transactions.bullion_rate_fix_id')
        //     ->select('transactions.*')
        //     ->selectRaw('(transactions.quantity*bullion_rate_fixes.rate) - sum(IFNULL(payment_transaction.amount,0)) as pending')
        //     ->havingRaw('pending > 0')
        //     ->groupby('transactions.id')
        //     ->where('bullion_rate_fixes.bullion_id', 1)
        //     ->orderBy('id', 'asc')
        //     ->get();
        // dd(DB::getQueryLog());
        $bullions = Bullion::where('status', 1)->get();
        // $users = User::where('status', 1)->get();
        $users = User::all();
        $paymentModes = PaymentMode::all();
        return view('payment.create', compact('bullions', 'users', 'paymentModes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'pay_date' => 'required|date',
            'transferred_by' => 'required|exists:users,id',
            'bullion' => 'required|exists:bullions,id',
            'paymentMode' => 'required|exists:payment_modes,id',
            'amount' => 'required|numeric|gt:0',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        $input['payment_mode_id'] = $request->get('paymentMode');
        $input['created_by'] = $request->user()->id;
        // if ($request->user()->role == 0) {
        //     $input['pay_date'] = now();
        // }
        DB::beginTransaction();
        try {
            $payment = Payment::create($input);
            $this->makePaymentTransaction($payment);
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Payment added successfully.");
        return redirect()->route('payments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-payments')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $bullions = Bullion::where('status', 1)->get();
        // $users = User::where('status', 1)->get();
        $users = User::all();
        $paymentModes = PaymentMode::all();
        // dd($payment);
        return view('payment.edit', compact('bullions', 'users', 'paymentModes', 'payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'pay_date' => 'required|date',
            'transferred_by' => 'required|exists:users,id',
            'bullion' => 'required|exists:bullions,id',
            'paymentMode' => 'required|exists:payment_modes,id',
            'amount' => 'required|numeric|gt:0',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        $input['payment_mode_id'] = $request->get('paymentMode');
        $input['updated_by'] = $request->user()->id;
        DB::beginTransaction();
        try {
            $isDirty = ($payment->amount != $input['amount']);
            $payment->update($input);
            if ($isDirty) {
                $payment->transactions()->detach();
                $this->makePaymentTransaction($payment);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Payment updated successfully.");
        return redirect()->route('payments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-payments')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $payment->transactions()->detach();
        $payment->delete();
        Session::flash('success_message', "Payment transaction deleted successfully.");
        return redirect()->route('payments.index');
    }

    private function makePaymentTransaction(Payment $payment)
    {
        // DB::enableQueryLog();
        $transactions = Transaction::query()->leftJoin('payment_transaction', 'transactions.id', 'payment_transaction.transaction_id')
            ->leftJoin('bullion_rate_fixes', 'bullion_rate_fixes.id', 'transactions.bullion_rate_fix_id')
            ->select('transactions.*')
            ->selectRaw('(transactions.quantity*bullion_rate_fixes.rate*0.10) - sum(IFNULL(payment_transaction.amount,0)) as pending')
            ->havingRaw('pending > 0')
            ->groupby('transactions.id')
            ->where('bullion_rate_fixes.bullion_id', $payment->bullion_id)
            ->orderBy('id', 'asc')
            ->get();
        // dd(DB::getQueryLog());
        if ($transactions->count()) {
            $booking_amount = $payment->amount;
            foreach ($transactions as $key => $transaction) {
                $amount = 0;
                if ($booking_amount < $transaction->pending) {
                    $amount = $booking_amount;
                } else {
                    $amount = $transaction->pending;
                }
                $payment->transactions()->attach($transaction->id, ['amount' => $amount]);
                $booking_amount -= $amount;
                if ($booking_amount <= 0) {
                    break;
                }
            }
        }
    }
}
