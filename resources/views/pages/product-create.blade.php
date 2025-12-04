@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Product - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Product</h2>
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

                <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Product Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Product Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="product_name"
                                value="{{ old('product_name') }}"
                                placeholder="Enter product name"
                                required
                            />
                        </div>

                        <!-- Barcode Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Barcode Number</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="barcode_number"
                                value="{{ $barcode }}"
                                placeholder="Enter barcode"
								minlength="8"
								maxlength="12"
								pattern="\d{8,}"
								readonly
                            />
                        </div>

                        <!-- Tool Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Tool Code</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="tool_code"
                                 value="{{ old('tool_code', $nextToolCode) }}"
                                placeholder="Enter tool code" readonly
                            />
                        </div>
						
						<!-- Product Type -->
						<div class="col-span-12 sm:col-span-6">
							<x-base.form-label>Product Type</x-base.form-label>
							<x-base.form-select name="product_type">
								<option value="">Select Product Type</option>
								<option value="consumable" {{ old('product_type') == 'consumable' ? 'selected' : '' }}>Consumable</option>
								<option value="repairable" {{ old('product_type') == 'repairable' ? 'selected' : '' }}>Repairable</option>
							</x-base.form-select>
						</div>

                        <!-- Category
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Category *</x-base.form-label>
                            <x-base.form-select name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-base.form-select>
                        </div> -->

                        <!-- Product Company -->
                        {{-- <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Product Company</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="product_company"
                                value="{{ old('product_company') }}"
                                placeholder="Enter company name"
                            />
                        </div> --}}

                        <!-- HSN Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>HSN Code</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="hsn_code"
                                value="{{ old('hsn_code') }}"
                                placeholder="Enter HSN code"
                            />
                        </div>

                        <!-- Minimum Rate -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Minimum Rate</x-base.form-label>
                            <x-base.form-input
                                type="number"
                                step="0.01"
                                name="minimum_rate"
                                value="{{ old('minimum_rate') }}"
                                placeholder="0.00"
                            />
                        </div>

                        <!-- Maximum Rate -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Maximum Rate</x-base.form-label>
                            <x-base.form-input
                                type="number"
                                step="0.01"
                                name="maximum_rate"
                                value="{{ old('maximum_rate') }}"
                                placeholder="0.00"
                            />
                        </div>

                        <!-- Minimum Quantity -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Minimum Quantity</x-base.form-label>
                            <x-base.form-input
                                type="number"
                                name="minimum_quantity"
                                value="{{ old('minimum_quantity', 0) }}"
                                placeholder="0"
                            />
                        </div>

                        <!-- Reorder Quantity 
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Reorder Quantity</x-base.form-label>
                            <x-base.form-input
                                type="number"
                                name="reorder_quantity"
                                value="{{ old('reorder_quantity', 0) }}"
                                placeholder="0"
                            />
                        </div>-->

                        <!-- Unit Type -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Unit Type *</x-base.form-label>
                            <x-base.form-select name="unit_id" required>
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->symbol }})
                                    </option>
                                @endforeach
                            </x-base.form-select>
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Product Photo</x-base.form-label>
                            <x-base.form-file-input
                                name="product_photo"
                                type="file"
                                accept="image/*" />
                            <p class="text-xs text-slate-500 mt-2">Max file size: 2MB. Supported: JPEG, PNG, JPG, GIF</p>
                        </div>
                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('products.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Create Product</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
