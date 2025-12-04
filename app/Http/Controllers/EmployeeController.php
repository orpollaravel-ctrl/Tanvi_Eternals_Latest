<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $perPage = 25;
            $search = $request->get('search', '');

            $query = Employee::query()->latest();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%');
                });
            }

            $employees = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $employees->items(),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'has_more' => $employees->hasMorePages(),
            ]);
        }

        // Initial load with first 25 records
        $employees = Employee::query()->latest()->paginate(25);

        return view('pages.employee.index', [
            'layout' => 'side-menu',
            'employees' => $employees,
            'employeesPaginator' => $employees,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
			do {
        $barcode = rand(10000000, 99999999);
		} while (\App\Models\Employee::where('barcode', $barcode)->exists());
        return view('pages/employee/create', [
            'layout' => 'side-menu',
			'barcode' => $barcode,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    $validated = $request->validate([
        'name' => ['nullable', 'string', 'max:255'],
        'code' => ['nullable', 'string', 'max:255'],
        'barcode' => ['nullable', 'string', 'max:255','unique:employees,barcode'],
        'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'active' => ['nullable', 'boolean'],
    ]);

    $imagePath = null;

    if ($request->hasFile('images')) {

        // Create folder if not exists
        $folderPath = public_path('storage/employees');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $request->file('images')->getClientOriginalExtension();

        // Move file directly
        $request->file('images')->move($folderPath, $filename);

        // Save path to DB
        $imagePath = 'employees/' . $filename;
    }

    Employee::create([
        'name' => $validated['name'] ?? null,
        'code' => $validated['code'] ?? null,
        'barcode' => $validated['barcode'] ?? null,
        'images' => $imagePath,
        'active' => $validated['active'] ?? 0,
    ]);

    return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $employee = Employee::findOrFail($id);
        return view('pages/employee/show', [
            'layout' => 'side-menu',
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $employee = Employee::findOrFail($id);
		$employees = Employee::get();
        return view('pages/employee/edit', [
            'layout' => 'side-menu',
            'employee' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $employee = Employee::findOrFail($id);

    $validated = $request->validate([
        'name' => ['nullable', 'string', 'max:255'],
        'code' => ['nullable', 'string', 'max:255'],
        'barcode' => ['nullable', 'string', 'max:255','unique:employees,barcode,'.$id],
        'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        'active' => ['nullable', 'boolean'],
    ]);

    $imagePath = $employee->images;

    if ($request->hasFile('images')) {

        // Delete old image
        if ($employee->images && file_exists(public_path('storage/' . $employee->images))) {
            unlink(public_path('storage/' . $employee->images));
        }

        // Create folder if not exists
        $folderPath = public_path('storage/employees');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Generate new filename
        $filename = time() . '_' . uniqid() . '.' . $request->file('images')->getClientOriginalExtension();

        // Move new file
        $request->file('images')->move($folderPath, $filename);

        // Save new path
        $imagePath = 'employees/' . $filename;
    }

    $employee->update([
        'name' => $validated['name'] ?? $employee->name,
        'code' => $validated['code'] ?? $employee->code,
        'barcode' => $validated['barcode'] ?? $employee->barcode,
        'images' => $imagePath,
        'active' => $validated['active'] ?? $employee->active,
    ]);

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
	
	 /**
     * Search employees for API (used in tool assign forms)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            // Return initial list of employees when query is empty (for dropdown opening)
            $employees = Employee::orderBy('name', 'asc')
                ->limit(50)
                ->get();
        } else {
            // Search employees when user types
            $employees = Employee::where('name', 'like', '%' . $query . '%')
                ->orWhere('code', 'like', '%' . $query . '%')
                ->orWhere('barcode', 'like', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->limit(50)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'code' => $employee->code,
                    'barcode' => $employee->barcode,
                ];
            })
        ]);
    }
	public function toggleActive(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->active = !$employee->active;
        $employee->save();

        return response()->json([
            'success' => true,
            'active' => $employee->active,
            'message' => 'Employee status updated successfully.'
        ]);
    }
}
