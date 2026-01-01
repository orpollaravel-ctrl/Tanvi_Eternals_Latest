<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-expenses')) {
           abort(403,'Permission Denied');
        }
        $expenses = Expense::with('salesman')
        ->orderByDesc('created_at')
        ->get()
        ->groupBy('salesman_id');
        return view('expenses/expense', [
            'layout' => 'side-menu',
            'expenses' => $expenses,
        ]);
    }

    public function create(): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('create-expenses')) {
           abort(403,'Permission Denied');
        }
        $salesman = Employee::whereHas('department', function ($q) {
             $q->whereRaw('LOWER(name) = ?', ['sales']);
            })->orderBy('name')->get();
        return view('expenses/expense-create', [
            'layout' => 'side-menu',
            'salesman' => $salesman
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:travel expense,food expense,hotel expense,other expense'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remark' => ['nullable', 'string'],
            'bill_upload' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'salesman_id' => ['required', 'exists:employees,id'],
        ]);

        if ($request->hasFile('bill_upload')) {
            $file = $request->file('bill_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $fileName);
            $validated['bill_upload'] = $fileName;
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(string $salesman_id): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-expenses')) {
           abort(403,'Permission Denied');
        }
        $salesman = Employee::findOrFail($salesman_id);

        $expenses = Expense::where('salesman_id', $salesman_id)
            ->orderByDesc('date')
            ->get();    
        return view('expenses/expense-show', [
            'layout' => 'side-menu',
             'salesman' => $salesman,
            'expenses' => $expenses,
        ]);
    }

    public function view(string $id): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('view-expenses')) {
           abort(403, 'Permission Denied');
        }
        $expense = Expense::findOrFail($id);
        return view('expenses/expense-view', [
            'layout' => 'side-menu',
            'expense' => $expense,
        ]);
    }

    public function edit(string $id): View
    {
         if (!auth()->check() || !auth()->user()->hasPermission('edit-expenses')) {
           abort(403,'Permission Denied');
        }
        $expense = Expense::findOrFail($id);
        $salesman = Employee::whereHas('department', function ($q) {
        $q->whereRaw('LOWER(name) = ?', ['sales']);
            })->orderBy('name')->get();
        return view('expenses/expense-edit', [
            'layout' => 'side-menu',
            'expense' => $expense,
            'salesman' => $salesman
        ]);
    }

    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'in:travel expense,food expense,hotel expense,other expense'],
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remark' => ['nullable', 'string'],
            'bill_upload' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
               'salesman_id' => ['required', 'exists:employees,id'],
        ]);

        if ($request->hasFile('bill_upload')) {
            if ($expense->bill_upload && file_exists(public_path('uploads/expenses/' . $expense->bill_upload))) {
                unlink(public_path('uploads/expenses/' . $expense->bill_upload));
            }
            $file = $request->file('bill_upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/expenses'), $fileName);
            $validated['bill_upload'] = $fileName;
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(string $id)
    {
         if (!auth()->check() || !auth()->user()->hasPermission('delete-expenses')) {
           abort(403,'Permission Denied');
        }
        $expense = Expense::findOrFail($id);
        
        if ($expense->bill_upload && file_exists(public_path('uploads/expenses/' . $expense->bill_upload))) {
            unlink(public_path('uploads/expenses/' . $expense->bill_upload));
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $status = $request->status;
        
        if (!in_array($status, ['approved', 'rejected'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status']);
        }
        
        $expense->update(['status' => $status]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully',
            'status' => $status
        ]);
    }
}