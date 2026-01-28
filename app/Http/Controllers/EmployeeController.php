<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-employees')) {
           abort(403,'Permission Denied');
        }
        if ($request->ajax()) {
            $page = $request->get('page', 1);
            $perPage = 25;
            $search = $request->get('search', '');

        $query = Employee::with('department')->latest();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%')
                        ->orWhere('barcode', 'like', '%' . $search . '%');
                });
            }

            $employees = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'data' => $employees->map(function ($employee) {
                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'code' => $employee->code,
                        'barcode' => $employee->barcode,
                        'images' => $employee->images,
                        'active' => $employee->active,
                        'department' => optional($employee->department)->name,
                        'salary' => $employee->salary,
                        'target' => $employee->target,
                    ];
                }),
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'has_more' => $employees->hasMorePages(),
            ]);
        }

            $employees = Employee::with('department')->latest()->paginate(25);

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
         if (!auth()->check() || !auth()->user()->hasPermission('create-employees')) {
           abort(403,'Permission Denied');
        }
			do {
        $barcode = rand(10000000, 99999999);
		} while (\App\Models\Employee::where('barcode', $barcode)->exists());
            $departments = Department::orderBy('name')->get();

        return view('pages/employee/create', [
            'layout' => 'side-menu',
			'barcode' => $barcode,
            'departments' => $departments,
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
            'barcode' => ['nullable', 'string', 'max:255', 'unique:employees,barcode'],
            'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'active' => ['nullable', 'boolean'], 
            'department_id' => ['required', 'exists:departments,id'],
            'monthly_target_hours' => ['required', 'integer', 'min:1'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
        ]);

        $imagePath = null;

        if ($request->hasFile('images')) {
            $folderPath = public_path('storage/employees');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('images')->getClientOriginalExtension();
            $request->file('images')->move($folderPath, $filename);
            $imagePath = 'employees/' . $filename;
        }

        $employee = Employee::create([
            'name' => $validated['name'] ?? null,
            'code' => $validated['code'] ?? null,
            'barcode' => $validated['barcode'] ?? null,
            'images' => $imagePath,
            'active' => $validated['active'] ?? 0, 
            'department_id' => $validated['department_id'],
            'monthly_target_hours' => $validated['monthly_target_hours'] ?? 260,
            'monthly_salary' => $validated['monthly_salary'] ?? null,
        ]);

        $department = Department::find($validated['department_id']);
        if ($department && strtolower($department->name) === 'sales' && $validated['name']) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['code'] . '@gmail.com',
                'password' => Hash::make('password123'),  
                'gender' => 'male',  
                'active' => $validated['active'] ?? 0,
                'sales_id' => $employee->id,
            ]);

           $permissionIds = Permission::whereIn('group', ['quotation', 'expense'])
                ->pluck('id')
                ->toArray();

            if (!empty($permissionIds)) {
                $user->permissions()->sync($permissionIds);
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-employees')) {
           abort(403,'Permission Denied');
        }
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
         if (!auth()->check() || !auth()->user()->hasPermission('edit-employees')) {
           abort(403,'Permission Denied');
        }
        $employee = Employee::findOrFail($id);
		$employees = Employee::get();
        $departments = Department::orderBy('name')->get();

        return view('pages/employee/edit', [
            'layout' => 'side-menu',
            'employee' => $employee,
            'departments' => $departments,
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
            'barcode' => ['nullable', 'string', 'max:255', 'unique:employees,barcode,' . $id],
            'images' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'active' => ['nullable', 'boolean'],

            // ✅ NEW FIELDS
            'department_id' => ['required', 'exists:departments,id'],
            'monthly_target_hours' => ['required', 'integer', 'min:1'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
        ]);

        $imagePath = $employee->images;

        if ($request->hasFile('images')) {
            if ($employee->images && file_exists(public_path('storage/' . $employee->images))) {
                unlink(public_path('storage/' . $employee->images));
            }

            $folderPath = public_path('storage/employees');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('images')->getClientOriginalExtension();
            $request->file('images')->move($folderPath, $filename);
            $imagePath = 'employees/' . $filename;
        }

        $employee->update([
            'name' => $validated['name'] ?? $employee->name,
            'code' => $validated['code'] ?? $employee->code,
            'barcode' => $validated['barcode'] ?? $employee->barcode,
            'images' => $imagePath,
            'active' => $validated['active'] ?? $employee->active, 
            'department_id' => $validated['department_id'],
            'monthly_target_hours' => $validated['monthly_target_hours'],
            'monthly_salary' => $validated['monthly_salary'],
        ]);
        $department = Department::find($validated['department_id']);
        $employeeName = $validated['name'] ?? $employee->name;
        $employeeCode = $validated['code'] ?? $employee->code;
        
        if ($department && strtolower($department->name) === 'sales' && $employeeName && $employeeCode) {
            $email = $employeeCode . '@gmail.com';
            $userExists = User::where('email', $email)->first(); 
            if (!$userExists) {
                $user = User::create([
                    'name' => $employeeName,
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'gender' => 'male', 
                    'active' => $validated['active'] ?? $employee->active ?? 0,
                    'sales_id' => $employee->id,
                ]);

                $permissionIds = Permission::whereIn('group', ['quotation', 'expense'])
                    ->pluck('id')
                    ->toArray();

                if (!empty($permissionIds)) {
                    $user->permissions()->sync($permissionIds);
                }
            } else {
                $userExists->update(['sales_id' => $employee->id]); 
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

 
    public function destroy(string $id)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('delete-employees')) {
           abort(403,'Permission Denied');
        }
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
