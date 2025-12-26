<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest; 
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Imports\ClientsImport;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if(!auth()->check() || !auth()->user()->hasPermission('view-clients')) {
            abort(403, 'Permission Denied');
        }

        $clients = Client::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('pages.client', [
            'layout' => 'side-menu',
            'clients' => $clients,
            'search' => $request->search,  
        ]);
    }
 
    public function create(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-clients')) {
            abort(403,'Permission Denied');
        }
        $salesman = Employee::whereHas('department', function ($q) {
                $q->where('name', 'Sales');
            })->orderBy('name')->get();
            
        return view('pages.client-create',['salesman' => $salesman]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        
        $data = $request->validated();

        unset($data['password_confirmation']);
        $data['password'] = Hash::make($data['password']);
        $client =  Client::create($data);
        $client->assignDefaultPermissions();
        return redirect()->route('client.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-clients')) {
            abort(403,'Permission Denied');
        }
        $client = Client::findOrFail($id);
        $salesman = Employee::whereHas('department', function ($q) {
                $q->where('name', 'Sales');
            })->orderBy('name')->get();
        return view('pages.client-edit', [
            'layout' => 'side-menu',
            'client' => $client,
            'salesman' => $salesman,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, string $id)
    {  
        $client = Client::findOrFail($id);

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

            unset($validated['password_confirmation']);

        $client->update($validated);
        $client->assignDefaultPermissions();
        return redirect()
            ->route('client.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-clients')) {
            abort(403,'Permission Denied');
        }
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('client.index')->with('success', 'Client deleted successfully.');
    }

    public function import(Request $request)
    { 
        if (!auth()->user()->hasPermission('create-clients')) {
            abort(403, 'Permission Denied');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $import = new ClientsImport(); 
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->isNotEmpty()) {
            return back()->with([
                'import_errors' => $failures
            ]);
        }

        return redirect()
            ->route('client.index')
            ->with('success', 'Clients imported successfully.');
    }
}
