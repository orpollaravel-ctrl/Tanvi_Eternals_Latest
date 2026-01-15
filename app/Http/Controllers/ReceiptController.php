<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Bullion;
use App\Models\BullionRateFix;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReceiptController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(function ($request, $next) {            
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-mr')) {
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
        if (!auth()->check() || !auth()->user()->hasPermission('view-metal-receipts')) {
           abort(403,'Permission Denied');
        }
        $bullions = Bullion::where('status', 1)->get();
        // DB::enableQueryLog();        
        $receipts = Receipt::with('bullion', 'createdBy', 'updatedBy')
        ->when($request->get('bullion'), function ($q) use ($request) {
            return $q->where('bullion_id', $request->get('bullion'));
        })->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
            return $q->whereBetween('receipt_date', [$request->get('from_date'), $request->get('to_date')]);
        })->latest()->paginate(10);
        // dd(DB::getQueryLog());
        return view('receipt.index', compact('receipts','bullions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-metal-receipts')) {
           abort(403,'Permission Denied');
        }
        $bullions = Bullion::where('status', 1)->get();
        return view('receipt.create', compact('bullions'));
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
            'receipt_date' => 'required|date',
            'bullion' => 'required|exists:bullions,id',
            'quantity' => 'required|numeric|gt:0',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        $input['created_by'] = $request->user()->id;
        // if($request->user()->role==0){
        //     $input['receipt_date'] = now();
        // }
        DB::beginTransaction();
        try {
            $receipt = Receipt::create($input);
            $this->makeTransaction($receipt);
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong." . $e->getMessage());
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Material Receipt added successfully.");
        return redirect()->route('receipts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function edit(Receipt $receipt)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-metal-receipts')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $bullions = Bullion::where('status', 1)->get();
        return view('receipt.edit', compact('bullions', 'receipt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'receipt_date' => 'required|date',
            'bullion' => 'required|exists:bullions,id',
            'quantity' => 'required|numeric|gt:0',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        $input['updated_by'] = $request->user()->id;
        DB::beginTransaction();
        try {
            $isDirty = $receipt->quantity != $input['quantity'];
            $receipt->update($input);
            if ($isDirty) {
                $receipt->transactions()->each(function ($transaction) {
                    $transaction->delete();
                });
                // Transaction::where('receipt_id',$receipt->id)->delete();
                $this->makeTransaction($receipt);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong." . $e->getMessage());
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Material Receipt updated successfully.");
        return redirect()->route('receipts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-metal-receipts')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $receipt->delete();
        Session::flash('success_message', "Material Receipt deleted successfully.");
        return redirect()->route('receipts.index');
    }

   private function makeTransaction(Receipt $receipt)
	{
		$brfs = BullionRateFix::query()
			->leftJoin('transactions', 'bullion_rate_fixes.id', 'transactions.bullion_rate_fix_id')
			->select(
				'bullion_rate_fixes.id',
				'bullion_rate_fixes.quantity',
				'bullion_rate_fixes.rate',
				'bullion_rate_fixes.bullion_id'
			)
			->selectRaw('bullion_rate_fixes.quantity - SUM(IFNULL(transactions.quantity, 0)) AS pending')
			->where('bullion_rate_fixes.bullion_id', $receipt->bullion_id)
			->groupBy(
				'bullion_rate_fixes.id',
				'bullion_rate_fixes.quantity',
				'bullion_rate_fixes.rate',
				'bullion_rate_fixes.bullion_id'
			)
			->havingRaw('pending > 0')
			->orderBy('bullion_rate_fixes.id', 'asc')
			->get();

		if ($brfs->isEmpty()) {
			return;
		}

		$booking_qty = $receipt->quantity;

		foreach ($brfs as $brf) {

			$transaction = new Transaction();
			$transaction->bullion_rate_fix_id = $brf->id;
			$transaction->quantity = ($booking_qty < $brf->pending) ? $booking_qty : $brf->pending;

			$receipt->transactions()->save($transaction);

			$transaction = Transaction::find($transaction->id);

			$realBrf = BullionRateFix::find($brf->id);

			if ($realBrf) {
				$this->makePaymentTransaction($transaction, $realBrf);
			}

			$booking_qty -= $transaction->quantity;

			if ($booking_qty <= 0) {
				break;
			}
		}
	}
	private function makePaymentTransaction(Transaction $transaction, BullionRateFix $brf)
	{
		$payments = DB::select("
			SELECT 
				p.id,
				p.amount,
				(p.amount - IFNULL(SUM(pt.amount),0)) AS pending
			FROM payments p
			LEFT JOIN payment_transaction pt 
				ON p.id = pt.payment_id
			WHERE p.bullion_id = ?
			GROUP BY p.id, p.amount
			HAVING pending > 0
			ORDER BY p.id ASC
		", [$brf->bullion_id]);

		if (!$payments) return;

		// FIX 1: use transaction quantity instead of undefined $qty
		$booking_amount = $transaction->quantity * $brf->rate * 0.10;

		foreach ($payments as $payment) {

			$assignAmount = min($booking_amount, $payment->pending);

			// FIX 2: use $transaction->id instead of undefined $transactionId
			DB::insert("
				INSERT INTO payment_transaction 
					(payment_id, transaction_id, amount)
				VALUES (?, ?, ?)
			", [
				$payment->id,
				$transaction->id,
				$assignAmount
			]);

			$booking_amount -= $assignAmount;

			if ($booking_amount <= 0) break;
		}
	}
}
