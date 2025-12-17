<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Dsr;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DsrController extends Controller
{
    public function index(): View
    {
        if (!auth()->user()->hasPermission('view-dsr')) {
            abort(403, 'Permission Denied');
        }

        $dsrs = Dsr::with('client')->latest()->get();

        return view('dsr.index', [
            'layout' => 'side-menu',
            'dsrs' => $dsrs
        ]);
    }

    public function create(): View
    {
        if (!auth()->user()->hasPermission('create-dsr')) {
            abort(403, 'Permission Denied');
        }

        $clients = Client::select('id', 'name', 'mobile_number','client_type')->get();

        return view('dsr.create', [
            'layout' => 'side-menu',
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'client_type' => ['required'],
            'no_of_shops' => ['nullable', 'integer'],
            'visiting_card_photo' => ['nullable', 'image', 'max:2048'],
            'shop_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('visiting_card_photo')) {
            $file = $request->file('visiting_card_photo');
            $fileName = time() . '_vc_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/visiting_cards'), $fileName);
            $validated['visiting_card_photo'] = $fileName;
        }

        if ($request->hasFile('shop_photo')) {
            $file = $request->file('shop_photo');
            $fileName = time() . '_shop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/shop_photos'), $fileName);
            $validated['shop_photo'] = $fileName;
        }

        Dsr::create($validated);

        return redirect()->route('dsr.index')
            ->with('success', 'DSR created successfully.');
    }

    public function edit(string $id): View
    {
        if (!auth()->user()->hasPermission('edit-dsr')) {
            abort(403, 'Permission Denied');
        }

        $dsr = Dsr::findOrFail($id);
        $clients = Client::select('id', 'name', 'mobile_number','client_type')->get();

        return view('dsr.edit', [
            'layout' => 'side-menu',
            'dsr' => $dsr,
            'clients' => $clients
        ]);
    }

    public function update(Request $request, string $id)
    {
        $dsr = Dsr::findOrFail($id);

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'client_type' => ['required'],
            'no_of_shops' => ['nullable', 'integer'],
            'visiting_card_photo' => ['nullable', 'image', 'max:2048'],
            'shop_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('visiting_card_photo')) {
            if ($dsr->visiting_card_photo &&
                file_exists(public_path('uploads/dsr/visiting_cards/' . $dsr->visiting_card_photo))) {
                unlink(public_path('uploads/dsr/visiting_cards/' . $dsr->visiting_card_photo));
            }

            $file = $request->file('visiting_card_photo');
            $fileName = time() . '_vc_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/visiting_cards'), $fileName);
            $validated['visiting_card_photo'] = $fileName;
        }

        if ($request->hasFile('shop_photo')) {
            if ($dsr->shop_photo &&
                file_exists(public_path('uploads/dsr/shop_photos/' . $dsr->shop_photo))) {
                unlink(public_path('uploads/dsr/shop_photos/' . $dsr->shop_photo));
            }

            $file = $request->file('shop_photo');
            $fileName = time() . '_shop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/dsr/shop_photos'), $fileName);
            $validated['shop_photo'] = $fileName;
        }

        $dsr->update($validated);

        return redirect()->route('dsr.index')
            ->with('success', 'DSR updated successfully.');
    }

    public function destroy(string $id)
    {
        if (!auth()->user()->hasPermission('delete-dsr')) {
            abort(403, 'Permission Denied');
        }

        $dsr = Dsr::findOrFail($id);

        if ($dsr->visiting_card_photo &&
            file_exists(public_path('uploads/dsr/visiting_cards/' . $dsr->visiting_card_photo))) {
            unlink(public_path('uploads/dsr/visiting_cards/' . $dsr->visiting_card_photo));
        }

        if ($dsr->shop_photo &&
            file_exists(public_path('uploads/dsr/shop_photos/' . $dsr->shop_photo))) {
            unlink(public_path('uploads/dsr/shop_photos/' . $dsr->shop_photo));
        }

        $dsr->delete();

        return redirect()->route('dsr.index')
            ->with('success', 'DSR deleted successfully.');
    }
}
