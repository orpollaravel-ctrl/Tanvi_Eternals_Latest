<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(): View
    {
        $expenses = Expense::latest()->get();
        return view('expenses/expense', [
            'layout' => 'side-menu',
            'expenses' => $expenses,
        ]);
    }

    public function create(): View
    {
        return view('expenses/expense-create', [
            'layout' => 'side-menu',
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

    public function edit(string $id): View
    {
        $expense = Expense::findOrFail($id);
        return view('expenses/expense-edit', [
            'layout' => 'side-menu',
            'expense' => $expense,
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
        $expense = Expense::findOrFail($id);
        
        if ($expense->bill_upload && file_exists(public_path('uploads/expenses/' . $expense->bill_upload))) {
            unlink(public_path('uploads/expenses/' . $expense->bill_upload));
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}