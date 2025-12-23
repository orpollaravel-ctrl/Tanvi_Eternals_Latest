<?php

namespace App\Http\Controllers;

use App\Models\ToolAssign;
use App\Models\ToolAssignItem;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Exports\EmployeeWiseToolAssignExport;
use Maatwebsite\Excel\Facades\Excel;

class ToolAssignController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-tool-issues')) {
            abort(403, 'Permission Denied');
        }

        $query = ToolAssign::with([
            'department',
            'items.employee',
            'items.product'
        ]);
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('department', fn ($q) =>
                $q->where('name', 'like', "%{$search}%")
            )->orWhereHas('items.employee', fn ($q) =>
                $q->where('name', 'like', "%{$search}%")
            )->orWhereHas('items.product', fn ($q) =>
                $q->where('product_name', 'like', "%{$search}%")
            );
        }
 
        if ($request->filled('employee_id')) {
            $query->whereHas('items', fn ($q) =>
                $q->where('emp_id', $request->employee_id)
            );
        }
 
        if ($request->filled('product_id')) {
            $query->whereHas('items', fn ($q) =>
                $q->where('product_id', $request->product_id)
            );
        }
 
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
 
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
 
        if ($request->ajax()) {
            $offset = (int) $request->get('offset', 0);
            $limit  = 25;

            $data = $query->skip($offset)->take($limit + 1)->get();

            return response()->json([
                'data' => $data->take($limit),
                'has_more' => $data->count() > $limit,
            ]);
        }
 
        return view('pages.tool_assigns.index', [
            'toolAssigns' => $query->take(25)->get(),
            'employees'   => Employee::all(),
            'departments' => Department::all(),
        ]);
    }
    
    public function create()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('create-tool-issues')) {
            abort(403,'Permission Denied');
        }
        $employees = Employee::all();
        $departments = Department::all();
        $products = Product::all();
        return view('pages.tool_assigns.create', compact('employees', 'departments', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'd_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'product_id' => 'required|array',
            'product_id.*' => 'nullable|integer',
            'serial_number' => 'nullable|array',
            'serial_number.*' => 'nullable|string|max:255',
            'add_quantity' => 'required|array',
            'add_quantity.*' => 'nullable|integer',
            'emp_id' => 'required|array',
            'emp_id.*' => 'nullable|integer',
        ]);
		 
        $errors = [];
        $oldInput = $request->all();
        
        foreach ($validated['product_id'] as $index => $product_id) {
            if (!empty($product_id) && !empty($validated['add_quantity'][$index])) {
                $quantity = $validated['add_quantity'][$index];
 
                $purchases = \App\Models\PurchaseItem::where('product_id', $product_id)
                    ->with('purchase')
                    ->get()
                    ->sortBy(function($pi) {
                        return $pi->purchase->bill_date ?? $pi->purchase->created_at;
                    });

                $totalQty = $purchases->sum('quantity') + \App\Models\OpeningStock::where('product_id', $product_id)->sum('quantity');
                $totalAssigned = \App\Models\ToolAssignItem::where('product_id', $product_id)->sum('quantity');

                $remainingQty = $totalQty;
                $deducted = 0;
                foreach ($purchases as $pi) {
                    if ($deducted >= $totalAssigned) break;
                    $toDeduct = min($pi->quantity, $totalAssigned - $deducted);
                    $remainingQty -= $toDeduct;
                    $deducted += $toDeduct;
                }

                if ($quantity > $remainingQty) {
                    $errors['add_quantity.' . $index] = 'Cannot assign more than remaining quantity (' . $remainingQty . ') for this product.';
                    // Clear only the invalid entries
                    $oldInput['product_id'][$index] = '';
                    $oldInput['add_quantity'][$index] = '';
                    $oldInput['emp_id'][$index] = '';
                }
            }
        }
        
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput($oldInput);
        }
		
        $toolAssign = ToolAssign::create([
            'd_id' => $validated['d_id'],
            'date' => $validated['date'],
        ]);

        foreach ($validated['product_id'] as $index => $product_id) {
            if (!empty($product_id) || !empty($validated['add_quantity'][$index]) || !empty($validated['emp_id'][$index])) {
                ToolAssignItem::create([
                    'tool_assign_id' => $toolAssign->id,
                    'product_id' => $product_id,
                    'serial_number' => $validated['serial_number'][$index] ?? null,
                    'quantity' => $validated['add_quantity'][$index] ?? null,
                    'emp_id' => $validated['emp_id'][$index] ?? null,
                ]);
            }
        }

        return redirect()->route('tool-assigns.index')
            ->with('success', 'Tool assignment created successfully.');
    }

    public function show(ToolAssign $toolAssign)
    {
        $toolAssign->load(['department', 'items.product', 'items.employee']);

        // Calculate inventory for each product in the tool assign
        $inventory = [];
        foreach ($toolAssign->items as $item) {
            $productId = $item->product_id;

            // Get purchases ordered by bill_date or created_at for FIFO
            $purchases = \App\Models\PurchaseItem::where('product_id', $productId)
                ->with('purchase')
                ->get()
                ->sortBy(function($pi) {
                    return $pi->purchase->bill_date ?? $pi->purchase->created_at;
                });

            $totalQty = $purchases->sum('quantity');
            $totalValue = $purchases->sum('amount');

            // Total assigned quantity across all tool assigns for this product
            $totalAssigned = \App\Models\ToolAssignItem::where('product_id', $productId)->sum('quantity');

            // FIFO deduction
            $remainingQty = $totalQty;
            $remainingValue = $totalValue;
            $deducted = 0;
            foreach ($purchases as $pi) {
                if ($deducted >= $totalAssigned) break;
                $toDeduct = min($pi->quantity, $totalAssigned - $deducted);
                $remainingQty -= $toDeduct;
                $remainingValue -= ($toDeduct / $pi->quantity) * $pi->amount;
                $deducted += $toDeduct;
            }

            $inventory[$productId] = [
                'product_name' => $item->product->product_name ?? 'N/A',
                'total_purchased_qty' => $totalQty,
                'total_purchased_value' => $totalValue,
                'total_assigned_qty' => $totalAssigned,
                'remaining_qty' => $remainingQty,
                'remaining_value' => $remainingValue,
            ];
        }

        return view('pages.tool_assigns.show', compact('toolAssign', 'inventory'));
    }

    public function edit(ToolAssign $toolAssign)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('edit-tool-issues')) {
            abort(403,'Permission Denied');
        }
        $employees = Employee::all();
        $departments = Department::all();
        $products = Product::all();

        return view('pages.tool_assigns.edit', compact('toolAssign', 'employees', 'departments', 'products'));
    }

    public function update(Request $request, ToolAssign $toolAssign)
    {
        $validated = $request->validate([
            'd_id' => 'nullable|integer',
            'date' => 'nullable|date',
            'product_id' => 'required|array',
            'product_id.*' => 'nullable|integer',
            'serial_number' => 'nullable|array',
            'serial_number.*' => 'nullable|string|max:255',
            'add_quantity' => 'required|array',
            'add_quantity.*' => 'nullable|integer',
            'emp_id' => 'required|array',
            'emp_id.*' => 'nullable|integer',
        ]);

        foreach ($validated['product_id'] as $index => $product_id) {
            if (!empty($product_id) && !empty($validated['add_quantity'][$index])) {
                $quantity = $validated['add_quantity'][$index];
 
                $purchases = \App\Models\PurchaseItem::where('product_id', $product_id)
                    ->with('purchase')
                    ->get()
                    ->sortBy(function($pi) {
                        return $pi->purchase->bill_date ?? $pi->purchase->created_at;
                    });

                $totalQty = $purchases->sum('quantity') + \App\Models\OpeningStock::where('product_id', $product_id)->sum('quantity');
                $totalAssigned = \App\Models\ToolAssignItem::where('product_id', $product_id)
                    ->where('tool_assign_id', '!=', $toolAssign->id)
                    ->sum('quantity');

                $remainingQty = $totalQty;
                $deducted = 0;
                foreach ($purchases as $pi) {
                    if ($deducted >= $totalAssigned) break;
                    $toDeduct = min($pi->quantity, $totalAssigned - $deducted);
                    $remainingQty -= $toDeduct;
                    $deducted += $toDeduct;
                }

                if ($quantity > $remainingQty) {
                    return back()->withErrors(['add_quantity.' . $index => 'Cannot assign more than remaining quantity (' . $remainingQty . ') for this product.'])->withInput();
                }
            }
        }

        $toolAssign->update([
            'd_id' => $validated['d_id'],
            'date' => $validated['date'],
        ]);

        $toolAssign->items()->delete();

        foreach ($validated['product_id'] as $index => $product_id) {
            if (!empty($product_id) || !empty($validated['add_quantity'][$index]) || !empty($validated['emp_id'][$index])) {
                ToolAssignItem::create([
                    'tool_assign_id' => $toolAssign->id,
                    'product_id' => $product_id,
                    'serial_number' => $validated['serial_number'][$index] ?? null,
                    'quantity' => $validated['add_quantity'][$index] ?? null,
                    'emp_id' => $validated['emp_id'][$index] ?? null,
                ]);
            }
        }

        return redirect()->route('tool-assigns.index')
            ->with('success', 'Tool assignment updated successfully.'); 
    }

	public function destroy(ToolAssign $toolAssign)
    {
        if (!auth()->check() || !auth()->user()->hasPermission('delete-tool-issues')) {
            abort(403,'Permission Denied');
        }
        try {
            // Delete associated tool assign items first
            $toolAssign->items()->delete();

            $toolAssign->delete();

            return redirect()->route('tool-assigns.index')
                ->with('success', 'Tool assignment and associated items deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('tool-assigns.index')
                ->with('error', 'Unable to delete tool assignment. It may be referenced by other records.');
        }
    }
	
	public function purchaseReport(Request $request)
    {
        set_time_limit(120);
        
        $query = \App\Models\Purchase::with(['vendor', 'items.product']);

        if ($request->filled('start_date')) {
            $query->where('bill_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('bill_date', '<=', $request->end_date);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $purchases = $query->latest('bill_date')->paginate(20);
        $vendors = \App\Models\Vendor::all();

        return view('pages.tool_assigns.reports.purchase-report', compact('purchases', 'vendors'));
    }

    public function productReport(Request $request)
    {
        $query = \App\Models\Product::with(['category', 'purchaseItems', 'toolAssignItems']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10)->through(function ($product) {
            $product->total_purchased = $product->purchaseItems->sum('quantity') + \App\Models\OpeningStock::where('product_id', $product->id)->sum('quantity');
            $product->total_assigned = $product->toolAssignItems->sum('quantity');
            $product->avg_rate = $product->purchaseItems->avg('rate') ?? 0;
            return $product;
        });

        $categories = \App\Models\Category::all();

        return view('pages.tool_assigns.reports.product-report', compact('products', 'categories'));
    }

    public function departmentWiseReport(Request $request)
    {
        $query = \App\Models\ToolAssign::with(['department', 'items.product', 'items.employee']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->filled('department_id')) {
            $query->where('d_id', $request->department_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('department', function ($dept) use ($search) {
                    $dept->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('items.employee', function ($emp) use ($search) {
                    $emp->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('items.product', function ($prod) use ($search) {
                    $prod->where('product_name', 'like', '%' . $search . '%');
                });
            });
        }


        $toolAssigns = $query->get();

        // Filter departments based on request
        if ($request->filled('department_id')) {
            $departments = \App\Models\Department::where('id', $request->department_id)->get();
        } else {
            $departments = \App\Models\Department::all();
        }

        return view('pages.tool_assigns.reports.department-wise-report', compact('toolAssigns', 'departments'));
    }

    public function employeeWiseReport(Request $request)
    {
        $query = \App\Models\ToolAssign::with(['department', 'items.product', 'items.employee']);

        // Apply filters
        if ($request->filled('employee_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('emp_id', $request->employee_id);
            });
        }

        if ($request->filled('start_date')) {
            try {
                $startDate = \Carbon\Carbon::parse($request->start_date)->format('Y-m-d');
                $query->whereDate('date', '>=', $startDate);
            } catch (\Exception $e) {
                // Invalid date format, ignore filter
            }
        }

        if ($request->filled('end_date')) {
            try {
                $endDate = \Carbon\Carbon::parse($request->end_date)->format('Y-m-d');
                $query->whereDate('date', '<=', $endDate);
            } catch (\Exception $e) {
                // Invalid date format, ignore filter
            }
        }

        $toolAssigns = $query->get();
        $departments = \App\Models\Department::all();

        // Filter employees based on request
        if ($request->filled('employee_id')) {
            $employees = \App\Models\Employee::where('id', $request->employee_id)->get();
        } else {
            $employees = \App\Models\Employee::all();
        }
        return view('pages.tool_assigns.reports.employee-wise-report', compact('toolAssigns', 'departments', 'employees'));
    }

    public function history(Request $request)
    {
        $productId = $request->get('product_id');
        $empId = $request->get('emp_id');

        if (!$productId || !$empId) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID and Employee ID are required.'
            ], 400);
        }

        $history = ToolAssignItem::where('product_id', $productId)
            ->where('emp_id', $empId)
            ->with(['toolAssign', 'product', 'employee'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->toolAssign->date ? $item->toolAssign->date->format('Y-m-d') : $item->created_at->format('Y-m-d'),
                    'quantity' => $item->quantity,
                    'department' => $item->toolAssign->department->name ?? 'N/A',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function exportEmployeeWiseReport(Request $request)
    {
        // 🔹 Reuse SAME logic as report page
        $employees = Employee::query()->get();

        $toolAssigns = ToolAssign::with(['items.product.purchaseItems', 'department'])
            ->when($request->employee_id, function ($q) use ($request) {
                $q->whereHas('items', function ($qq) use ($request) {
                    $qq->where('emp_id', $request->employee_id);
                });
            })
            ->when($request->start_date, fn($q) => $q->whereDate('date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('date', '<=', $request->end_date))
            ->get();

        return Excel::download(
            new EmployeeWiseToolAssignExport($employees, $toolAssigns),
            'employee-wise-tool-assign-report.xlsx'
        );
    }
}
