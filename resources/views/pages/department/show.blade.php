@extends('layouts.side-menu')

@section('subcontent')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Department Details
    </h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary shadow-md mr-2">Edit</a>
        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Back to List</a>
    </div>
</div>

<div class="intro-y box p-5 mt-5">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">ID</label>
            <div class="form-control-plaintext">{{ $department->id }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Name</label>
            <div class="form-control-plaintext">{{ $department->name ?? 'N/A' }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Code</label>
            <div class="form-control-plaintext">{{ $department->code ?? 'N/A' }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Created At</label>
            <div class="form-control-plaintext">{{ $department->created_at->format('Y-m-d H:i:s') }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6">
            <label class="form-label">Updated At</label>
            <div class="form-control-plaintext">{{ $department->updated_at->format('Y-m-d H:i:s') }}</div>
        </div>
    </div>
</div>
@endsection
