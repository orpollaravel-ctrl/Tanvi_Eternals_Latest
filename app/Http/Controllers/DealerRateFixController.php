<?php

namespace App\Http\Controllers;

use App\Models\BullionRateFix;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Dealer;
use App\Models\DealerRateFix;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class DealerRateFixController extends Controller
{
   /* public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-drf')) {
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
        if (!auth()->check() || !auth()->user()->hasPermission('view-dealer-rate-fixes')) {
            abort(403, 'Permission Denied');
        }
    
        $clients = Client::get();
        $perPage = $request->get('per_page', 10);
    
        $query = DealerRateFix::with('client', 'fixedBy', 'createdBy', 'updatedBy')
            ->withCount('deals')
            ->when($request->get('client'), function ($q) use ($request) {
                $q->where('client_id', $request->get('client'));
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($q) use ($request) {
                $q->whereBetween('drf_date', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            })
            ->latest();
    
        $drfs = $query->paginate($perPage)->withQueryString();
    
        $totals = DealerRateFix::query()
            ->when($request->get('client'), function ($q) use ($request) {
                $q->where('client_id', $request->get('client'));
            })
            ->when($request->get('from_date') && $request->get('to_date'), function ($q) use ($request) {
                $q->whereBetween('drf_date', [
                    $request->get('from_date'),
                    $request->get('to_date')
                ]);
            })
            ->selectRaw('SUM(quantity) as total_quantity, SUM(amount) as total_amount, COUNT(*) as total_count')
            ->first();
    
        return view('drf.index', compact('drfs', 'clients', 'totals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-dealer-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        $clients = Client::get();
        // $users = User::where('status', 1)->get();
        $users = User::all();
        return view('drf.create', compact('clients', 'users'));
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
            'drf_date' => 'required|date',
            'fixed_by' => 'required|exists:users,id',
            'client' => 'required|exists:clients,id',
            'quantity' => 'required|numeric|gt:0',
            'rate' => 'required|numeric|gt:9999',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['client']);
        // if ($request->user()->role == 0) {
        //     $input['drf_date'] = now();
        // }
        $input['client_id'] = $request->get('client');
        $drf = DealerRateFix::create($input);
        $this->makeDeal($drf);
        $msg = "Thank you for deal Fixing. Your Deal Id-{#var#},Booking Qty-{#var#},Booking Rate-{#var#}.Remarks:{#var#} Note: If any correction please know us with in one hour.Tanvi Gold Cast LLP";
        $this->sendSMS($msg, $drf);
        // DB::enableQueryLog();        
        // $brfs=BullionRateFix::with('deals')->get();
        // dd(DB::getQueryLog());


        Session::flash('success_message', "Client Rate Fixed successfully.");
        return redirect()->route('drfs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DealerRateFix  $drf
     * @return \Illuminate\Http\Response
     */
    public function show(DealerRateFix $drf)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DealerRateFix  $drf
     * @return \Illuminate\Http\Response
     */
    public function edit(DealerRateFix $drf)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-dealer-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $clients = Client::get();
        // $users = User::where('status', 1)->get();
        $users = User::all();
        return view('drf.edit', compact('clients', 'users', 'drf'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DealerRateFix  $drf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DealerRateFix $drf)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'drf_date' => 'required|date',
            'fixed_by' => 'required|exists:users,id',
            'client' => 'required|exists:clients,id',
            'quantity' => 'required|numeric|gt:0',
            'rate' => 'required|numeric|gt:9999',
            'remark' => 'nullable|string|max:255'
        ];
        $this->validate($request, $rules);
        $input = $request->except(['client']);
        $input['client_id'] = $request->get('client');
        DB::beginTransaction();
        try {
            $isDirty = ($drf->quantity != $input['quantity'] || $drf->rate != $input['rate']);
            $drf->update($input);
            if ($isDirty) {
                Deal::where('dealer_rate_fix_id', $drf->id)->delete();
                $this->makeDeal($drf);
                $msg = "Thank you for deal Fixing Your Deal Id- {#var#} Updated: Booking Qty-{#var#},Booking Rate-{#var#}.Remarks:{#var#} Note: If any correction please know us with in one hour.Tanvi Gold Cast LLP";
                $this->sendSMS($msg, $drf);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('error_message', "Something went wrong." . $e->getMessage());
            return redirect()->back()->withInput();
        }
        DB::commit();
        Session::flash('success_message', "Client Rate Fix updated successfully.");
        return redirect()->route('drfs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DealerRateFix  $drf
     * @return \Illuminate\Http\Response
     */
    public function destroy(DealerRateFix $drf)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-dealer-rate-fixes')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $msg = "Thank you for deal Fixing Your Deal Id- {#var#},has been Canceled. Note:If any correction please know us with in one hour.Tanvi Gold Cast LLP";
        $this->sendSMS($msg, $drf);
        Deal::where('dealer_rate_fix_id', $drf->id)->delete();
        $drf->delete();
        Session::flash('success_message', "Client Rate Fix deleted successfully.");
        return redirect()->route('drfs.index');
    }

    private function makeDeal(DealerRateFix $drf)
    {
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
			->orderBy('rate', 'desc')
			->where('rate', '<', $drf->rate)
			->get();

        if ($brfs->count()) {
            $booking_qty = round($drf->quantity, 3);
            foreach ($brfs as $key => $brf) {
                $deal = new Deal();
                $deal->bullion_rate_fix_id = $brf->id;
                if ($booking_qty < $brf->pending) {
                    $deal->quantity = $booking_qty;
                } else {
                    $deal->quantity = $brf->pending;
                }
                $drf->deals()->save($deal);
                $booking_qty -= $brf->pending;
                if ($booking_qty <= 0) {
                    break;
                }
            }
        }
    }

    private function sendSMS($msg, $drf)
    {
        $msg=Str::replaceFirst("{#var#}", $drf->id, $msg);
        $msg=Str::replaceFirst("{#var#}", $drf->quantity, $msg);
        $msg=Str::replaceFirst("{#var#}", $drf->rate, $msg);
        $msg=Str::replaceFirst("{#var#}", $drf->remark, $msg);
        $dphone = $drf->client->mobile_number;
        // dd($msg);
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
            <mobile>'.$dphone.'</mobile>
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
