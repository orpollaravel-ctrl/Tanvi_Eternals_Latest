@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Employee - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Employee</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
                @if ($errors->any())
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger dark:border-danger/30">
                        <div class="font-medium">There were some problems with your input.</div>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Employee Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Employee Name *</x-base.form-label>
                            <x-base.form-input type="text" name="name" value="{{ old('name') }}"
                                placeholder="Enter employee name" required />
                        </div>

                        <!-- Employee Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Employee Code</x-base.form-label>
                            <x-base.form-input type="text" name="code" value="{{ old('code') }}"
                                placeholder="Enter employee code" />
                        </div>

                        <!-- Employee Barcode -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Employee Barcode</x-base.form-label>
                            <x-base.form-input type="text" name="barcode" value="{{ $barcode }}"
                                placeholder="Enter employee barcode" readonly />
                        </div>
                        <!-- Department -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Department *</x-base.form-label>
                            <x-base.tom-select name="department_id" class="w-full" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" @selected(old('department_id') == $dept->id)>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </x-base.tom-select>
                        </div>

                        <!-- Monthly Target Hours -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Monthly Target (Hours) *</x-base.form-label>
                            <x-base.form-input type="number" name="monthly_target_hours"
                                value="{{ old('monthly_target_hours', 260) }}" min="1" required />
                        </div>

                        <!-- Monthly Salary -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Monthly Salary</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" name="monthly_salary"
                                value="{{ old('monthly_salary') }}" placeholder="Enter monthly salary" />
                        </div>
                        <!-- Employee Image -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Employee Image</x-base.form-label>
                            <input type="file" name="images" class="form-control" accept="image/*">
                        </div>
                        <!-- Active Status -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Active Status</x-base.form-label>
                            <div class="flex items-center">
                                <input type="checkbox" id="active" name="active" value="1" class="form-check-input"
                                    checked>
                                <label for="active" class="ml-2 text-slate-700 dark:text-slate-300">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('employees.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save Employee</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
