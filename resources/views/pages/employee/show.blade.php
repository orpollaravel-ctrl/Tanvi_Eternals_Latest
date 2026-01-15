@extends('layouts.side-menu')

@section('subcontent')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Employee Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary shadow-md mr-2">Edit</a>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>
</div>

<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
		<div class="col-span-12 sm:col-span-6">
            <label class="form-label">Image</label>
            <div class="form-control-plaintext">
                @if($employee->images)
                    <img src="{{ asset('storage/' . $employee->images) }}" alt="Employee Image" class="w-32 h-32 object-cover rounded">
                @else
                      <img class="h-16" src="{{ Vite::asset('resources/images/tanvi-5911910b.svg') }}" alt="Tanvi">
                @endif
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">ID</label>
            <div class="form-control-plaintext">{{ $employee->id }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Name</label>
            <div class="form-control-plaintext">{{ $employee->name ?? 'N/A' }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Code</label>
            <div class="form-control-plaintext">{{ $employee->code ?? 'N/A' }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Barcode</label>
            <div class="form-control-plaintext">{{ $employee->barcode ?? 'N/A' }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Department</label>
            <div class="form-control-plaintext">
                {{ $employee->department?->name ?? 'N/A' }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Monthly Target (Hours)</label>
            <div class="form-control-plaintext">
                {{ $employee->monthly_target_hours ?? 260 }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Monthly Salary</label>
            <div class="form-control-plaintext">
                @if($employee->monthly_salary)
                    ₹ {{ number_format($employee->monthly_salary, 2) }}
                @else
                    N/A
                @endif
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Hourly Rate</label>
            <div class="form-control-plaintext">
                @if($employee->monthly_salary && $employee->monthly_target_hours)
                    ₹ {{ number_format($employee->monthly_salary / $employee->monthly_target_hours, 2) }} / hr
                @else
                    N/A
                @endif
            </div>
        </div>


		 <div class="col-span-12 sm:col-span-6">
    <label class="form-label">Active</label>

    <div class="flex items-center">
        @if($employee->active)
            <span class="flex items-center bg-green-100 text-green-700 border border-green-300 px-3 py-1 rounded-full text-xs font-semibold">
                <x-base.lucide icon="CheckCircle" class="w-4 h-4 mr-1" />
                Active
            </span>
        @else
            <span class="flex items-center bg-red-100 text-red-700 border border-red-300 px-3 py-1 rounded-full text-xs font-semibold">
                <x-base.lucide icon="XCircle" class="w-4 h-4 mr-1" />
                Inactive
            </span>
        @endif
    </div>
</div>

        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Created At</label>
            <div class="form-control-plaintext">{{ $employee->created_at->format('Y-m-d H:i:s') }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Updated At</label>
            <div class="form-control-plaintext">{{ $employee->updated_at->format('Y-m-d H:i:s') }}</div>
        </div>
    </div>
</div>
@endsection
