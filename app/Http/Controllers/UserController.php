<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-users')) {
           abort(403,'Permission Denied');
        }
        
        $query = User::query();
        
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('contact_number', 'like', '%' . $search . '%');
            });
        }
        
        return view('pages/user', [
            'layout' => 'side-menu',
            'users' => $query->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-users')) {
            abort(403,'Permission Denied');
        }
        
        $permissions = Permission::all()->groupBy('group');
        return view('pages/user-create', [
            'layout' => 'side-menu',
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'gender' => ['required', 'in:male,female,other'],
            'contact_number' => ['required', 'string', 'max:50'],
            'active' => ['nullable', 'in:0,1'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'contact_number' => $validated['contact_number'],
            'active' => (bool) ($validated['active'] ?? false),
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'uploade/user';
            $file->move(public_path($filePath), $fileName);
            $user->update(['photo' => $fileName]);
        }

        $user->permissions()->sync($validated['permissions']);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-users')) {
            abort(403,'Permission Denied');
        }
        $user = User::findOrFail($id);
        $permissions = Permission::all();
        return view('pages/user-edit', [
            'layout' => 'side-menu',
            'user' => $user,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'gender' => ['nullable', 'in:male,female,other'],
            'contact_number' => ['required', 'string', 'max:50'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'active' => ['nullable', 'in:0,1'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (array_key_exists('gender', $validated)) {
            $user->gender = $validated['gender'];
        }
        
        if (array_key_exists('contact_number', $validated)) {
            $user->contact_number = $validated['contact_number'];
        }
        $user->active = (bool) ($validated['active'] ?? false);

        if (!empty($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) { 
            if ($user->photo && file_exists(public_path('uploads/user/' . $user->photo))) {
                unlink(public_path('uploads/user/' . $user->photo));
            }
            
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $filePath = 'uploads/user';
            $file->move(public_path($filePath), $fileName);
            $user->photo = $fileName;
        }

        $user->save();

        $user->permissions()->sync($validated['permissions']);

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-users')) {
            abort(403,'Permission Denied');
        }
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully.');   
    }
}
