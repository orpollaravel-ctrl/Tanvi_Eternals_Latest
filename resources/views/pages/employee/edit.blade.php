@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Employee - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Edit Employee
        </h2>
    </div>

    <div class="intro-y box p-5 mt-5">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-6">
                <!-- Employee Name -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="name" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Employee Name
                    </label>
                    <x-base.form-input
                        id="name"
                        name="name"
                        type="text"
                        class="!box"
                        placeholder="Enter employee name"
                        value="{{ old('name', $employee->name) }}"
                    />
                    @error('name')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Code -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="code" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Employee Code
                    </label>
                    <x-base.form-input
                        id="code"
                        name="code"
                        type="text"
                        class="!box"
                        placeholder="Enter employee code"
                        value="{{ old('code', $employee->code) }}"
                    />
                    @error('code')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Employee Barcode -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="barcode" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Employee Barcode
                    </label>
                    <x-base.form-input
                        id="barcode"
                        name="barcode"
                        type="text"
                        class="!box"
                        placeholder="Enter employee barcode"
                        value="{{ old('barcode', $employee->barcode) }}"
						readonly
                    />
                    @error('barcode')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
				
                <!-- Employee Image -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="images" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Employee Image
                    </label>
                    <input type="file" id="images" name="images" class="form-control" accept="image/*">
					
                    @if($employee->images)
                        <img  src="{{ asset('storage/' . $employee->images) }}" alt="Employee Image" class="mt-2 w-20 h-20 object-cover">
                    @endif
                    @error('images')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="col-span-12 sm:col-span-6">
                    <label for="active" class="form-label font-medium text-slate-700 dark:text-slate-300">
                        Active Status
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" id="active" name="active" value="1" class="form-check-input" {{ old('active', $employee->active) ? 'checked' : '' }}>
                        <label for="active" class="ml-2 text-slate-700 dark:text-slate-300">Active</label>
                    </div>
                    @error('active')
                        <div class="mt-2 text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex mt-8">
               <a href="{{ route('employees.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                   <x-base.button type="submit" variant="primary">Save Employee</x-base.button>
            </div>
        </form>
    </div>
@endsection

