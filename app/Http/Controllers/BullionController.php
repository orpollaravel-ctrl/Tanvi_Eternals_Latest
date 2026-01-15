<?php

namespace App\Http\Controllers;

use App\Models\Bullion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BullionController extends Controller
{
    /*public function __construct()
    {
        $this->middleware(function ($request, $next) {            
            $user = Auth::user();
            if ($user->role == 0 && !$user->hasPermission('add-bullion')) {
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
    public function index()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullions')) {
           abort(403,'Permission Denied');
        }
        $bullions=Bullion::withCount('deals')->paginate(10);
        return view('bullion.index', compact('bullions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullions')) {
           abort(403,'Permission Denied');
        }
        return view('bullion.create');
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
            'name' => 'required|string|max:255|unique:bullions,name',
            'phone' => 'required|regex:/^[0-9]{10}$/',
        ];
        $this->validate($request, $rules);
        $request->has('status') ?
            $request->merge(['status'=>1]) : $request->merge(['status'=>0]);
        Bullion::create($request->all());
        Session::flash('success_message', "Bullion added successfully");
        return redirect()->route('bullions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bullion $bullion)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-bullions')) {
           abort(403,'Permission Denied');
        }
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        return view('bullion.edit', compact('bullion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bullion $bullion)
    {
        // if (auth()->user()->role == 0) {
        //     return abort(403);
        // }
        $rules = [
            'name' => 'required|string|max:255|unique:bullions,name,'.$bullion->id,
            'phone' => 'required|regex:/^[0-9]{10}$/',
        ];
        $this->validate($request, $rules);
        $request->has('status') ?
            $request->merge(['status'=>1]) : $request->merge(['status'=>0]);
        $bullion->update($request->all());
        Session::flash('success_message', "Bullion added successfully");
        return redirect()->route('bullions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-bullions')) {
            abort(403,'Permission Denied');
        }
        $bullion = Bullion::findOrFail($id);
        $bullion->delete();

        return redirect()->route('bullions.index')->with('success', 'Bullion deleted successfully.');
    }
}
