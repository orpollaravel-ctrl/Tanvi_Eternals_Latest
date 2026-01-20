<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-expenses')) {
            abort(403, 'Permission Denied');
        }
        $query = Expense::with('salesman');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $expenses = $query
            ->latest()
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
            abort(403, 'Permission Denied');
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

    public function show(Request $request, string $salesman_id): View
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-expenses')) {
            abort(403, 'Permission Denied');
        }

        $salesman = Employee::findOrFail($salesman_id);

        $query = Expense::where('salesman_id', $salesman_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $expenses = $query
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
            abort(403, 'Permission Denied');
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
            abort(403, 'Permission Denied');
        }
        $expense = Expense::findOrFail($id);
        $salesmanId = $expense->salesman_id;

        if ($expense->bill_upload && file_exists(public_path('uploads/expenses/' . $expense->bill_upload))) {
            unlink(public_path('uploads/expenses/' . $expense->bill_upload));
        }

        $expense->delete();

        return redirect()->route('expenses.show', $salesmanId)->with('success', 'Expense deleted successfully.');
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

    public function print(Request $request)
    {
        $query = Expense::with('salesman');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }
        
        $expenses = $query->latest()->get();

        return view('expenses.expense-print', compact('expenses'));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $query = Expense::with('salesman');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('salesman_id')) {
            $query->where('salesman_id', $request->salesman_id);
        }
        
        $expenses = $query->latest()->get();

        $filename = 'expenses_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($expenses) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Salesman',
                'Type',
                'Amount',
                'Status',
                'Date',
                'Remark'
            ]);

            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->salesman->name ?? '-',
                    ucfirst($expense->type),
                    $expense->amount,
                    ucfirst($expense->status),
                    optional($expense->date)->format('d-m-Y'),
                    $expense->remark ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
