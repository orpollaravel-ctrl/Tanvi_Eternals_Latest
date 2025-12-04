@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Bullion Rate Fix - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Bullion Rate Fix</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">

                {{-- Error Block --}}
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

                {{-- Error Message --}}
                @if (Session::has('error_message'))
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('brfs.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="brf_date"
                                value="{{ old('brf_date', now()->format('Y-m-d')) }}"
                                @user readonly @enduser
                                required
                            />
                        </div>

                        <!-- Fixed By -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Fixed By *</x-base.form-label>
                            <x-base.form-select name="fixed_by">
                                <option value="0">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (old('fixed_by') == $user->id) selected @endif>
                                            {{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Bullion Name -->
                        <div class="col-span-12">
                            <x-base.form-label>Bullion Name *</x-base.form-label>
                            <x-base.form-select name="bullion">
                                <option value="0">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if (old('bullion') == $bullion->id) selected @endif>
                                            {{ $bullion->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Booking Quantity (In Grams) *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="quantity"
                                id="quantity"
                                value="{{ old('quantity') }}"
                                placeholder="Quantity"
                                required
                            />
                        </div>

                        <!-- Rate -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Booking Rate (for 10 Grams) *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="rate"
                                id="rate"
                                value="{{ old('rate') }}"
                                placeholder="Rate"
                                required
                            />
                        </div>

                        <!-- Total Value -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Total Value</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                id="amount"
                                name="amount"
                                value="{{ old('amount') }}"
                                readonly
                                placeholder=""
                            />
                        </div>

                        <!-- Remark -->
                        <div class="col-span-12">
                            <x-base.form-label>Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" rows="3">{{ old('remark') }}</x-base.form-textarea>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('brfs.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Bullion Rate Fix
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


