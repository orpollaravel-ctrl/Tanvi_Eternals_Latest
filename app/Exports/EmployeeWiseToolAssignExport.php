<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeWiseToolAssignExport implements FromView
{
    protected $employees;
    protected $toolAssigns;

    public function __construct($employees, $toolAssigns)
    {
        $this->employees = $employees;
        $this->toolAssigns = $toolAssigns;
    }

    public function view(): View
    {
        return view('exports.employee-wise-tool-assign', [
            'employees'   => $this->employees,
            'toolAssigns' => $this->toolAssigns,
        ]);
    }
}
