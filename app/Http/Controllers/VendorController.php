<?php

namespace App\Http\Controllers;

use App\Http\Requests\VendorRequest;
use App\Models\Vendor;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function index(): View
    {
        $vendors = Vendor::latest()->paginate(10);

        return view('pages.vendor', [
            'layout' => 'side-menu',
            'vendors' => $vendors,
        ]);
    }

    public function create(): View
    {
        return view('pages.vendor-create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(VendorRequest $request)
    {
        Vendor::create($request->validated());

        return redirect()->route('vendor.index')->with('success', 'Vendor created successfully.');
    }

    public function edit(string $id): View
    {
        $vendor = Vendor::findOrFail($id);

        return view('pages.vendor-edit', [
            'layout' => 'side-menu',
            'vendor' => $vendor,
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
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor deleted successfully.');
    }
}
