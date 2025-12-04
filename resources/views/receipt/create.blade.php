@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Metal Receipt - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Metal Receipt</h2>

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

                {{-- ERROR --}}
                @if (Session::has('error_message'))
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('receipts.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Bullion Name -->
                        <div class="col-span-12">
                            <x-base.form-label>Bullion Name *</x-base.form-label>
                            <x-base.form-select name="bullion" required>
                                <option value="">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if (old('bullion') == $bullion->id) selected @endif>
                                            {{ $bullion->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="receipt_date"
                                value="{{ old('receipt_date', now()->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Quantity (In Grams) *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="quantity"
                                value="{{ old('quantity') }}"
                                placeholder="Enter quantity"
                                required
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
                        <a href="{{ route('receipts.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Metal Receipt
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


