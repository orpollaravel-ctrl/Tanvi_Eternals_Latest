<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DealerController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(function ($request, $next) {            
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-dealer')) {
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
         if (!auth()->check() || !auth()->user()->hasPermission('view-bullions')) {
            abort(403,'Permission Denied');
        }
        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $perPage = 25;
            $search = $request->get('search', '');

            $query = Dealer::withCount('deals')->latest();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
                });
            }

            $dealers = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $dealers->items(),
                'current_page' => $dealers->currentPage(),
                'last_page' => $dealers->lastPage(),
                'has_more' => $dealers->hasMorePages(),
            ]);
        }

        // Initial load with first 25 dealers
        $dealers = Dealer::withCount('deals')->latest()->paginate(25);

        return view('dealer.index', compact('dealers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (!auth()->check() || !auth()->user()->hasPermission('create-bullions')) {
            abort(403,'Permission Denied');
        }
        return view('dealer.create');
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
            'name' => 'required|string|max:255|unique:dealers,name',
            'code' => 'required|string|max:255|unique:dealers,code',
            'email' => 'required|string|email|max:255|unique:dealers',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ];
        $this->validate($request, $rules);
        $request->has('status') ?
            $request->merge(['status'=>1]) : $request->merge(['status'=>0]);
        // dd($request->all());
        Dealer::create($request->all());
        Session::flash('success_message', "Dealer added successfully");
        return redirect()->route('dealers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\Response
     */
    public function show(Dealer $dealer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\Response
     */
    public function edit(Dealer $dealer)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('edit-bullions')) {
            abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        return view('dealer.edit', compact('dealer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dealer $dealer)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'name' => 'required|string|max:255|unique:dealers,name,'.$dealer->id,
            'code' => 'required|string|max:255|unique:dealers,code,'.$dealer->id,
            'email' => 'required|string|email|max:255|unique:dealers,email,'.$dealer->id,
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ];
        $this->validate($request, $rules);
        // dd($request->all());
        $request->has('status') ?
            $request->merge(['status'=>1]) : $request->merge(['status'=>0]);

        $dealer->update($request->all());
        Session::flash('success_message', "Dealer upddated successfully");
        return redirect()->route('dealers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dealer  $dealer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dealer $dealer)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('delete-bullions')) {
            abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        return redirect()->route('dealers.index');
    }
	
	/**
     * Import dealers from an uploaded Excel file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx'
        ]);

        $path = $request->file('file')->getRealPath();

        try {
            $data = Excel::toArray([], $request->file('file'));

            if (empty($data[0])) {
                return redirect()->back()->withErrors('Excel file is empty');
            }

            $rows = $data[0];
            $errors = [];
            $insertedCount = 0;

            foreach ($rows as $index => $row) {
                // Skip header row if detected (optional)
                if ($index == 0 && (in_array('name', array_map('strtolower', $row)) || in_array('code', array_map('strtolower', $row)))) {
                    continue;
                }

                $dealerData = [
                    'name' => $row[0] ?? null,
                    'code' => $row[1] ?? null,
                    'email' => $row[2] ?? null,
                    'phone' => $row[3] ?? null,
                    'address' => $row[4] ?? null,
                    'pincode' => $row[5] ?? null,
                    'location' => $row[6] ?? null,
                    'status' => $row[7] ?? 1,
                ];

                $validator = Validator::make($dealerData, [
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:255|unique:dealers,code',
                    'email' => 'required|string|email|max:255|unique:dealers',
                    'phone' => 'required|regex:/^[0-9]{10}$/',
                    'address' => 'nullable|string|max:255',
                    'pincode' => 'required|string|max:255',
                    'location' => 'nullable|string|max:255',
                    'status' => 'nullable|boolean',
                ]);

                if ($validator->fails()) {
                    $errors[] = [
                        'row' => $index + 1,
                        'email' => $dealerData['email'] ?? '',
                        'messages' => $validator->errors()->all(),
                    ];
                    continue;
                }

                Dealer::create($dealerData);
                $insertedCount++;
            }

            $successMessage = "$insertedCount dealers imported successfully.";

            return redirect()->route('dealers.index')
                ->with('success_message', $successMessage)
                ->with('validation_errors', $errors);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error processing Excel file: ' . $e->getMessage());
        }
    }
}
