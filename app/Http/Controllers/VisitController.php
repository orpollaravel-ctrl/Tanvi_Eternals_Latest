<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;


class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check() || !auth()->user()->hasPermission('view-visit')) {
            abort(403, 'Permission Denied');
        }

        $visits = Target::all();
        return view('visits/index', [
            'layout' => 'side-menu',
            'visits' => $visits,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $visit = Target::findOrFail($id);
        return view('visits.view', compact('visit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function print(): View
    { 
        $visits = Target::latest()->get();
        return view('visits/visit-print', [
            'visits' => $visits,
        ]); 
    }

    function exportExcel(): StreamedResponse
    {
        $targets = Target::latest()->get();
        
        $filename = 'targets_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($targets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer Name', 'Target Date', 'Phone', 'Time', 'Reason']);
            
            foreach ($targets as $target) {
                fputcsv($file, [
                    $target->customer_name,
                    $target->target_date,
                    $target->phone,
                    $target->time,
                    $target->reason,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
