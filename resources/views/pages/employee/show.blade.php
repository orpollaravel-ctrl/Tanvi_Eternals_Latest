@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Employee - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-10 flex items-center justify-between">
        <h2 class="text-lg font-medium">Employee Details</h2>

        <a href="{{ route('employees.index') }}">
            <x-base.button variant="secondary">
                Back
            </x-base.button>
        </a>
    </div>

    <div class="intro-y box mt-5 p-5">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Image</label>
                <div class="mt-1">
                    @if($employee->images)
                        <img src="{{ asset('storage/' . $employee->images) }}" alt="Employee Image" class="w-32 h-32 object-cover rounded">
                    @else
                        <img class="h-16" src="{{ url('uploads/logo.png') }}" alt="Default Image">
                    @endif
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">ID</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->id }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Name</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->name ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Code</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->code ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Barcode</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->barcode ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Department</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->department?->name ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Monthly Target (Hours)</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->monthly_target_hours ?? 260 }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Monthly Salary</label>
                <div class="mt-1 font-semibold">
                    @if($employee->monthly_salary)
                        ₹ {{ number_format($employee->monthly_salary, 2) }}
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Hourly Rate</label>
                <div class="mt-1 font-semibold">
                    @if($employee->monthly_salary && $employee->monthly_target_hours)
                        ₹ {{ number_format($employee->monthly_salary / $employee->monthly_target_hours, 2) }} / hr
                    @else
                        -
                    @endif
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Active</label>
                <div class="mt-1">
                    @if($employee->active)
                        <span class="inline-flex items-center bg-green-100 text-green-700 border border-green-300 px-3 py-1 rounded-full text-xs font-semibold">
                            <x-base.lucide icon="CheckCircle" class="w-4 h-4 mr-1" />
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center bg-red-100 text-red-700 border border-red-300 px-3 py-1 rounded-full text-xs font-semibold">
                            <x-base.lucide icon="XCircle" class="w-4 h-4 mr-1" />
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Created At</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->created_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Updated At</label>
                <div class="mt-1 font-semibold">
                    {{ $employee->updated_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="intro-y mt-5 flex flex-wrap gap-2">
        @if (auth()->check() && auth()->user()->hasPermission('edit-employees'))
            <a href="{{ route('employees.edit', $employee->id) }}">
                <x-base.button variant="primary">
                    Edit
                </x-base.button>
            </a>
        @endif
    </div>
@endsection
