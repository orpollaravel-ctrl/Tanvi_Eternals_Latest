@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Department - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Department
        </h2>
    </div>

    <div class="intro-y box p-5 mt-5">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-6">
                <!-- Department Name -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="name" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Department Name
                    </label>
                    <x-base.form-input
                        id="name"
                        name="name"
                        type="text"
                        class="!box"
                        placeholder="Enter department name"
                        value="{{ old('name', $department->name) }}"
                    />
                    @error('name')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department Code -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="code" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Department Code
                    </label>
                    <x-base.form-input
                        id="code"
                        name="code"
                        type="text"
                        class="!box"
                        placeholder="Enter department code"
                        value="{{ old('code', $department->code) }}"
                    />
                    @error('code')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex mt-8">
               <a href="{{ route('departments.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                   <x-base.button type="submit" variant="primary">Save Department</x-base.button>
            </div>
        </form>
    </div>
@endsection
