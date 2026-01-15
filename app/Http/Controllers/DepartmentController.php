<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-departments')) {
           abort(403,'Permission Denied');
        }
        $departments = Department::query()->latest()->get();
        return view('pages/department/index', [
            'layout' => 'side-menu',
            'departments' => $departments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-departments')) {
           abort(403,'Permission Denied');
        }
        return view('pages/department/create', [
            'layout' => 'side-menu',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255', 'unique:departments,name'],
            'code' => ['nullable', 'string', 'max:255','unique:departments,code'],
        ]);

        Department::create([
            'name' => $validated['name'] ?? null,
            'code' => $validated['code'] ?? null,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-departments')) {
           abort(403,'Permission Denied');
        }
        $department = Department::findOrFail($id);
        return view('pages/department/show', [
            'layout' => 'side-menu',
            'department' => $department,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-departments')) {
           abort(403,'Permission Denied');
        }
        $department = Department::findOrFail($id);
        return view('pages/department/edit', [
            'layout' => 'side-menu',
            'department' => $department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255','unique:departments,name,'.$id],
            'code' => ['nullable', 'string', 'max:255','unique:departments,code,'.$id],
        ]);

        $department->update([
            'name' => $validated['name'] ?? $department->name,
            'code' => $validated['code'] ?? $department->code,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-departments')) {
           abort(403,'Permission Denied');
        }
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
	/**
     * Search departments for API.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $departments = Department::where('name', 'LIKE', '%' . $query . '%')
            ->orWhere('code', 'LIKE', '%' . $query . '%')
            ->limit(50)
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'data' => $departments,
        ]);
    }
}
