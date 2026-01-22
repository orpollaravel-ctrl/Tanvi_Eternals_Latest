<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Models\Vendor;
use App\Models\Employee;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-vendors')) {
           abort(403,'Permission Denied');
        }
        $vendors = Vendor::latest()->get();

        return view('pages.vendor', [
            'layout' => 'side-menu',
            'vendors' => $vendors,
        ]);
    }

    public function create(): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('create-vendors')) {
           abort(403,'Permission Denied');
        }
        $employees = Employee::where('active', 1)->get();
        return view('pages.vendor-create', [
            'layout' => 'side-menu',
            'employees' => $employees,
        ]);
    }

    public function store(VendorRequest $request)
    {
        Vendor::create($request->validated());
        return redirect()->route('vendor.index')->with('success', 'Vendor created successfully.');
    }

    public function edit(string $id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-vendors')) {
           abort(403,'Permission Denied');
        }
        $vendor = Vendor::findOrFail($id);
        $employees = Employee::where('active', 1)->get();

        return view('pages.vendor-edit', [
            'layout' => 'side-menu',
            'vendor' => $vendor,
            'employees' => $employees,
        ]);
    }

    public function update(VendorRequest $request, string $id)
    {
         
        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->validated());

        return redirect()->route('vendor.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(string $id)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('delete-vendors')) {
           abort(403,'Permission Denied');
        }
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
