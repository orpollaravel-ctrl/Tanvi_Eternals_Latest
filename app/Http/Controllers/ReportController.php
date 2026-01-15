<?php

namespace App\Http\Controllers;

use App\Models\Bullion;
use App\Models\BullionRateFix;
use App\Models\Deal;
use App\Models\Dealer;
use App\Models\DealerRateFix;
use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function booking_comparision(Request $request)
    {
        /* @var User $user */
        $user = Auth::user();
        /*if ($user->role == 0 && !$user->hasPermission('browse-bc')) {
            return abort(403);
        }*/
        $dates[0]=now()->format('Y-m-d');
        $dates[1]=now()->format('Y-m-d');
        if($request->has('from_date') && $request->has('to_date')){
            $dates[0]=$request->get('from_date');
            $dates[1]=$request->get('to_date');
        }
        $bullions = Bullion::where('status',1)->get();
        $deals = Deal::with(['drf.client', 'brf.bullion'])
            ->whereHas('drf', function($q) use($dates){
                $q->whereBetween('drf_date', $dates);
            })
            ->when($request->get('bullion'), function ($q) use ($request) {
                return $q->whereHas('brf', function($sub) use ($request){
                    $sub->where('bullion_id', $request->get('bullion'));
                });
            })
            ->latest()->get();
        return view('report.booking_comparision', compact('deals','bullions'));
    }

    public function pending_deals(Request $request)
    {
        /* @var User $user */
        $user = Auth::user();
        /*if ($user->role == 0 && !$user->hasPermission('browse-pd')) {
            return abort(403);
        }*/
        // DB::enableQueryLog();        
        $deals = DealerRateFix::query()->leftJoin('deals', 'dealer_rate_fixes.id', 'deals.dealer_rate_fix_id')
            ->select('dealer_rate_fixes.*')
            ->selectRaw('round(dealer_rate_fixes.quantity,3) - sum(IFNULL(deals.quantity,0)) as pending')
            ->havingRaw('pending > 0')
            ->groupby('dealer_rate_fixes.id')
            ->orderBy('rate', 'asc')
            ->with(['client', 'fixedBy'])->get();
        // dd(DB::getQueryLog());
        // dd($deals);
        return view('report.pending_deals', compact('deals'));
    }
    public function bullion_pending_deals(Request $request)
    {
        /* @var User $user */
        $user = Auth::user();
        /*if ($user->role == 0 && !$user->hasPermission('browse-pd')) {
            return abort(403);
        }*/
        // DB::enableQueryLog();        
        $deals = BullionRateFix::query()->leftJoin('deals', 'bullion_rate_fixes.id', 'deals.bullion_rate_fix_id')
            ->select('bullion_rate_fixes.*')
            ->selectRaw('bullion_rate_fixes.quantity - sum(IFNULL(deals.quantity,0)) as pending')
            ->havingRaw('pending > 0')
            ->groupby('bullion_rate_fixes.id')
            ->orderBy('rate', 'asc')
            ->with(['bullion', 'fixedBy'])->get();
        // dd(DB::getQueryLog());
        // dd($deals);
        return view('report.bullion_pending_deals', compact('deals'));
    }

    public function bullion_ledger(Request $request)
    {

        /* @var User $user */
        $user = Auth::user();
        /*if ($user->role == 0 && !$user->hasPermission('browse-bla')) {
            return abort(403);
        }*/
        $bullions = Bullion::where('status',1)->get();
        $dates[0]=now()->format('Y-m-d');
        $dates[1]=now()->format('Y-m-d');
        if($request->has('from_date') && $request->has('to_date')){
            $dates[0]=$request->get('from_date');
            $dates[1]=$request->get('to_date');
        }
        $reciept=Receipt::selectRaw('IFNULL(SUM(quantity),0)')->whereColumn('bullion_rate_fixes.bullion_id', 'bullion_id')
        ->when($request->get('bullion'), function ($q) use ($request) {
            return $q->where('bullion_id', $request->get('bullion'));
        })->whereDate('receipt_date','<',$dates[0]);
        $payment=Payment::selectRaw('IFNULL(SUM(amount),0)')->whereColumn('bullion_rate_fixes.bullion_id', 'bullion_id')
        ->when($request->get('bullion'), function ($q) use ($request) {
            return $q->where('bullion_id', $request->get('bullion'));
        })->whereDate('pay_date','<',$dates[0]);
        // $brf=BullionRateFix::se
        // DB::enableQueryLog();
        $opening=BullionRateFix::select(DB::raw("(SUM(quantity)-({$reciept->toSql()})) as quantity,(SUM(amount)-({$payment->toSql()})) as amount"))
        ->when($request->get('bullion'), function ($q) use ($request) {
            return $q->where('bullion_id', $request->get('bullion'));
        })->whereDate('brf_date','<',$dates[0])->mergeBindings($reciept->getQuery())->mergeBindings($payment->getQuery())
        ->groupBy('bullion_rate_fixes.bullion_id')->first();
        // dd($opening);
        // dd(DB::getQueryLog());        
        $brfs = BullionRateFix::query()->leftJoin('bullions', 'bullions.id', 'bullion_rate_fixes.bullion_id')
            ->select(DB::raw("brf_date as date,bullions.name,quantity,rate,amount,remark,'Rate Fix' as transaction,0 as receipt_qty,0 as payment"))
            ->when($request->get('bullion'), function ($q) use ($request) {
                return $q->where('bullion_id', $request->get('bullion'));
            })->whereBetween('brf_date', $dates);
            // ->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
            //     return $q->whereBetween('brf_date', [$request->get('from_date'), $request->get('to_date')]);
            // });
        $reciepts = Receipt::query()->leftJoin('bullions', 'bullions.id', 'receipts.bullion_id')
            ->select(DB::raw("receipt_date as date,bullions.name,0 as quantity,0 as rate,0 as amount,remark,'Receipt' as transaction,quantity as receipt_qty,0 as payment"))
            ->when($request->get('bullion'), function ($q) use ($request) {
                return $q->where('bullion_id', $request->get('bullion'));
            })->whereBetween('receipt_date', $dates);
            // ->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
            //     return $q->whereBetween('receipt_date', [$request->get('from_date'), $request->get('to_date')]);
            // });
        $data = Payment::query()->leftJoin('bullions', 'bullions.id', 'payments.bullion_id')
            ->select(DB::raw("pay_date as date,bullions.name,0 as quantity,0 as rate,0 as amount,remark,'Payment' as transaction,0 receipt_qty,amount as payment"))
            ->unionall($brfs)->unionall($reciepts)->orderBy('date', 'asc')->when($request->get('bullion'), function ($q) use ($request) {
                return $q->where('bullion_id', $request->get('bullion'));
            })->whereBetween('pay_date', $dates)
            // ->when($request->get('from_date') && $request->get('from_date'), function ($q) use ($request) {
            //     return $q->whereBetween('pay_date', [$request->get('from_date'), $request->get('to_date')]);
            // })
            ->get();
        // dd($data);
        return view('report.bullion_ledger', compact('data', 'bullions','opening'));
    }
}
