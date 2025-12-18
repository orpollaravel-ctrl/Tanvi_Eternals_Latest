<?php

namespace App\Http\Controllers;

use App\Models\Bullion;
use Illuminate\Support\Facades\Auth;
use App\Models\BullionRateFix;
use App\Models\Deal;
use App\Models\DealerRateFix;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class BullionRateFixController extends Controller
{
   /* public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-brf')) {
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
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullion-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        $bullions = Bullion::where('status', 1)->get();
        // dd($brfs);
        $brfs = BullionRateFix::with('bullion', 'fixedBy', 'createdBy', 'updatedBy')
            ->when($request->get('bullion'), function ($q) use ($request) {
                return $q->where('bullion_id', $request->get('bullion'));
            })->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
                return $q->whereBetween('brf_date', [$request->get('from_date'), $request->get('to_date')]);
            })->latest()->paginate(10);
        return view('brf.index', compact('brfs', 'bullions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullion-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        $bullions = Bullion::where('status', 1)->get();
        // $users = User::where('status', 1)->get();
        $users = User::all();
        return view('brf.create', compact('bullions', 'users'));
    }

    public function manual_deal_create()
    {
        /*if (auth()->user()->role == 0) {
            return abort(403);
        }*/
        $columns = [
			'dealer_rate_fixes.id',
			'dealer_rate_fixes.client_id',
			'dealer_rate_fixes.fixed_by',
			'dealer_rate_fixes.created_by',
			'dealer_rate_fixes.updated_by',
			'dealer_rate_fixes.drf_date',
			'dealer_rate_fixes.quantity',
			'dealer_rate_fixes.rate',
			'dealer_rate_fixes.amount',
			'dealer_rate_fixes.remark',
			'dealer_rate_fixes.created_at',
			'dealer_rate_fixes.updated_at',
		];

		$drfs = DealerRateFix::query()
			->leftJoin('deals', 'dealer_rate_fixes.id', '=', 'deals.dealer_rate_fix_id')
			->select($columns)
			->selectRaw('ROUND(dealer_rate_fixes.quantity,3) - sum(IFNULL(deals.quantity,0)) as pending')
			->groupBy($columns)
			->havingRaw('pending > 0')
			->with('client')
			->get();

       $columns = [
			'bullion_rate_fixes.id',
			'bullion_rate_fixes.bullion_id',
			'bullion_rate_fixes.fixed_by',
			'bullion_rate_fixes.created_by',
			'bullion_rate_fixes.updated_by',
			'bullion_rate_fixes.brf_date',
			'bullion_rate_fixes.quantity',
			'bullion_rate_fixes.rate',
			'bullion_rate_fixes.amount',
			'bullion_rate_fixes.remark',
			'bullion_rate_fixes.created_at',
			'bullion_rate_fixes.updated_at',
		];

		$brfs = BullionRateFix::query()
			->leftJoin('deals', 'bullion_rate_fixes.id', '=', 'deals.bullion_rate_fix_id')
			->select($columns)
			->selectRaw('bullion_rate_fixes.quantity - sum(IFNULL(deals.quantity,0)) as pending')
			->groupBy($columns)
			->havingRaw('pending > 0')
			->with('bullion')
			->get();

        return view('brf.manual_deal_create', compact('drfs', 'brfs'));
    }

    public function manual_deal_store(Request $request)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'drf' => 'required|exists:dealer_rate_fixes,id',
            'brf' => 'required|exists:bullion_rate_fixes,id',
        ];
        $this->validate($request, $rules);
        $brf = BullionRateFix::query()->leftJoin('deals', 'bullion_rate_fixes.id', 'deals.bullion_rate_fix_id')
            ->select('bullion_rate_fixes.*')
            ->selectRaw('bullion_rate_fixes.quantity - sum(IFNULL(deals.quantity,0)) as pending')
            ->havingRaw('pending > 0')
            ->groupby('bullion_rate_fixes.id')
            ->where('bullion_rate_fixes.id', $request->get('brf'))->first();
        $drf = DealerRateFix::query()->leftJoin('deals', 'dealer_rate_fixes.id', 'deals.dealer_rate_fix_id')
            ->select('dealer_rate_fixes.*')->selectRaw('ROUND(dealer_rate_fixes.quantity,3) - sum(IFNULL(deals.quantity,0)) as pending')
            ->havingRaw('pending > 0')->groupby('dealer_rate_fixes.id')->where('dealer_rate_fixes.id', $request->get('drf'))->first();
        if ($brf && $drf) {
            $deal = new Deal();
            $deal->bullion_rate_fix_id = $brf->id;  
            $deal->dealer_rate_fix_id  = $drf->id;
            $deal->quantity = min($brf->pending, $drf->pending);
            $deal->save();
        } else {
            Session::flash('error_message', "Selected deals not found,please try again.");
            return redirect()->back();
        }
        Session::flash('success_message', "Manual deal created successfully.");
        return redirect()->back();
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
            'brf_date' => 'required|date',
            'fixed_by' => 'required|exists:users,id',
            'bullion' => 'required|exists:bullions,id',
            'quantity' => 'required|numeric|gt:0',
            'rate' => 'required|numeric|gt:9999',
            'remark' => 'nullable|string|max:255'
        ];
		
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        // if ($request->user()->role == 0) {
        //     $input['brf_date'] = now();
        // }
        DB::beginTransaction();
        try {
            $brf = BullionRateFix::create($input);
            $this->makeDeal($brf);
            $this->makeTransaction($brf);
            $msg = "Thank you for deal Fixing. Your Deal Id-{#var#},Booking Qty-{#var#},Booking Rate-{#var#}.Remarks:{#var#} Note:If any correction please know us with in one hour.Tanvi Gold Cast LLP";
            $this->sendSMS($msg, $brf);
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong." . $e->getMessage());
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Bullion Rate Fixed successfully.");
        return redirect()->route('brfs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BullionRateFix  $bullionRateFix
     * @return \Illuminate\Http\Response
     */
    public function show(BullionRateFix $bullionRateFix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BullionRateFix  $bullionRateFix
     * @return \Illuminate\Http\Response
     */
    public function edit(BullionRateFix $brf)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullion-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $bullions = Bullion::where('status', 1)->get();
        // $users = User::where('status', 1)->get();
        $users = User::all();

        return view('brf.edit', compact('bullions', 'users', 'brf'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BullionRateFix  $bullionRateFix
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BullionRateFix $brf)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'brf_date' => 'required|date',
            'fixed_by' => 'required|exists:users,id',
            'bullion' => 'required|exists:bullions,id',
            'quantity' => 'required|numeric|gt:0',
            'rate' => 'required|numeric|gt:9999',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['bullion']);
        $input['bullion_id'] = $request->get('bullion');
        DB::beginTransaction();
        try {
            $isDirty = ($brf->quantity != $input['quantity'] || $brf->rate != $input['rate']);
            $brf->update($input);
            if ($isDirty) {
                Deal::where('bullion_rate_fix_id', $brf->id)->delete();
                $brf->transactions()->each(function ($transaction) {
                    $transaction->delete();
                });
                $this->makeDeal($brf);
                $this->makeTransaction($brf);
                $msg = "Thank you for deal Fixing Your Deal Id- {#var#} Updated: Booking Qty- {#var#},Booking Rate-{#var#}.Remarks:{#var#} Note: If any correction please know us with in one hour. Tanvi Gold Cast LLP";
                $this->sendSMS($msg, $brf);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Bullion Rate Fixed updated successfully.");
        return redirect()->route('brfs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BullionRateFix  $bullionRateFix
     * @return \Illuminate\Http\Response
     */
    public function destroy(BullionRateFix $brf)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullion-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        DB::beginTransaction();
        try {
            $msg = "Thank you for deal Fixing Your Deal Id- {#var#} ,has been Canceled. Note: If any correction please know us with in one hour.Tanvi Gold Cast LLP";
            $this->sendSMS($msg, $brf);
            $brf->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong.");
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Bullion Rate Fix deleted successfully.");
        return redirect()->route('brfs.index');
    }

    private function makeDeal(BullionRateFix $brf)
    {
		 if (!BullionRateFix::find($brf->id)) {
            return;
        }
        $drfs = DealerRateFix::query()->leftJoin('deals', 'dealer_rate_fixes.id', 'deals.dealer_rate_fix_id')
            ->select('dealer_rate_fixes.*')->selectRaw('ROUND(dealer_rate_fixes.quantity,3) - sum(IFNULL(deals.quantity,0)) as pending')
            ->havingRaw('pending > 0')->groupby('dealer_rate_fixes.id')->orderBy('rate', 'asc')->where('rate', '>', $brf->rate)->get();
        if ($drfs->count()) {
            $booking_qty = $brf->quantity;
            foreach ($drfs as $key => $drf) {
                $deal = new Deal();
                $deal->dealer_rate_fix_id = $drf->id;
                $deal->quantity = ($booking_qty < $drf->pending) ? $booking_qty : $drf->pending;
                $brf->deals()->save($deal);
                $booking_qty -= $deal->quantity;
                if ($booking_qty <= 0) {
                    break;
                }
            }
        }
    }

    private function makeTransaction(BullionRateFix $brf)
    {
        $receipts = Receipt::query()->leftJoin('transactions', 'receipts.id', 'transactions.receipt_id')
            ->select('receipts.*')->selectRaw('receipts.quantity - sum(IFNULL(transactions.quantity,0)) as pending')
            ->havingRaw('pending > 0')->groupby('receipts.id')->orderBy('id', 'asc')->where('bullion_id', $brf->bullion_id)->get();
        if ($receipts->count()) {
            $booking_qty = $brf->quantity;
            foreach ($receipts as $key => $receipt) {
                $transaction = new Transaction();
                $transaction->bullion_rate_fix_id = $brf->id;
                $transaction->quantity = ($booking_qty < $receipt->pending) ? $booking_qty : $receipt->pending;
                $receipt->transactions()->save($transaction);
                $this->makePaymentTransaction($transaction, $brf);
                $booking_qty -= $transaction->quantity;
                if ($booking_qty <= 0) {
                    break;
                }
            }
        }
    }
    private function makePaymentTransaction(Transaction $transaction, BullionRateFix $brf)
    {
        // DB::enableQueryLog();
        $payments = Payment::query()->leftJoin('payment_transaction', 'payments.id', 'payment_transaction.payment_id')
            ->select('payments.*')->selectRaw('payments.amount - sum(IFNULL(payment_transaction.amount,0)) as pending')
            ->havingRaw('pending > 0')->groupby('payments.id')->where('payments.bullion_id', $brf->bullion_id)->orderBy('id', 'asc')->get();
        // dd(DB::getQueryLog());
        if ($payments->count()) {
            $booking_amount = $transaction->quantity * $brf->rate * 0.10;
            foreach ($payments as $key => $payment) {
                $amount = $booking_amount < $payment->pending ? $booking_amount : $payment->pending;
                $transaction->payments()->attach($payment, ['amount' => $amount]);
                $booking_amount -= $amount;
                if ($booking_amount <= 0) {
                    break;
                }
            }
        }
    }
    private function sendSMS($msg, BullionRateFix $brf)
    {
        $msg = Str::replaceFirst("{#var#}", $brf->id, $msg);
        $msg = Str::replaceFirst("{#var#}", $brf->quantity, $msg);
        $msg = Str::replaceFirst("{#var#}", $brf->rate, $msg);
        $msg = Str::replaceFirst("{#var#}", $brf->remark, $msg);
        $dphone = $brf->bullion->phone;
        $xml_data = '<?xml version="1.0"?>
        <parent>
            <child>
            <user>TGCAST</user>
            <key>5757c0e67bXX</key>
            <mobile>9727756562</mobile>
            <message>' . $msg . '</message>
            <accusage>1</accusage>
            <senderid>TANVIG</senderid>
            </child>
            <child>
            <user>TGCAST</user>
            <key>5757c0e67bXX</key>
            <mobile>' . $dphone . '</mobile>
            <message>' . $msg . '</message>
            <accusage>1</accusage>
            <senderid>TANVIG</senderid>
            </child>            
        </parent>';

        $URL = "http://sms1.rightwayinfo.com/submitsms.jsp";

        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        // dd($output);
    }
}
