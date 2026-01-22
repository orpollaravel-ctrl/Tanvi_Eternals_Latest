@extends('../layouts/' . $layout)

@section('subhead')
    <title>View Department - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="intro-y mt-10 flex items-center justify-between">
        <h2 class="text-lg font-medium">Department Details</h2>

        <a href="{{ route('departments.index') }}">
            <x-base.button variant="secondary">
                Back
            </x-base.button>
        </a>
    </div>

    <div class="intro-y box mt-5 p-5">
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">ID</label>
                <div class="mt-1 font-semibold">
                    {{ $department->id }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Name</label>
                <div class="mt-1 font-semibold">
                    {{ $department->name ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Code</label>
                <div class="mt-1 font-semibold">
                    {{ $department->code ?? '-' }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Created At</label>
                <div class="mt-1 font-semibold">
                    {{ $department->created_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6">
                <label class="text-sm font-medium text-slate-500">Updated At</label>
                <div class="mt-1 font-semibold">
                    {{ $department->updated_at->format('Y-m-d H:i:s') }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="intro-y mt-5 flex flex-wrap gap-2">
        @if (auth()->check() && auth()->user()->hasPermission('edit-departments'))
            <a href="{{ route('departments.edit', $department->id) }}">
                <x-base.button variant="primary">
                    Edit
                </x-base.button>
            </a>
        @endif
    </div>
@endsection
