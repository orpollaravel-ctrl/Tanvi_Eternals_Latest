@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Bullion Rate - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Bullion Rate Fix</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="box p-5">

                {{-- Error Handling --}}
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

                <form method="POST" action="{{ route('bullion-rate.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="mt-3">
                            <x-base.form-label>Serial No*</x-base.form-label>
                            <x-base.form-input 
                                type="text" 
                                name="serial_no" 
                                value="{{ old('serial_no', $nextSerialNo) }}" 
                                readonly 
                                class="bg-gray-100 text-right cursor-not-allowed"/>
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Rate Cut On/Off*</x-base.form-label>
                            <x-base.form-select name="rate_cut_on_off">
                                <option value="online" {{ old('rate_cut_on_off') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="offline" {{ old('rate_cut_on_off') == 'offline' ? 'selected' : '' }}>Offline</option>
                            </x-base.form-select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Client Name*</x-base.form-label>
                            <x-base.form-input type="text" name="name" value="{{ old('name') }}" placeholder="Name" />
                        </div>
                        <div>
                            <x-base.form-label>Date*</x-base.form-label>
                            <x-base.form-input type="date" name="date" value="{{ old('date') }}" />
                        </div>
                    </div>

                    <!-- Quantity + Rate + Amount -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div>   
                            <x-base.form-label>Weight (in Gram)</x-base.form-label>
                            <x-base.form-input type="number" step="0.001" class="text-right" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" placeholder="0.000" />
                        </div>
                        <div>
                            <x-base.form-label>Rate (per Gram)</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="rate" name="rate" value="{{ old('rate', 0) }}" placeholder="0.00" />
                        </div>
                        <div>
                            <x-base.form-label>Amount</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="amount" name="amount" value="{{ old('amount', 0) }}" placeholder="0.00" readonly />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('bullion-rate') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get input elements
        const quantityInput = document.getElementById('quantity');
        const rateInput = document.getElementById('rate');
        const amountInput = document.getElementById('amount');

        // Function to calculate amount (quantity * rate)
        function calculateAmount(quantity, rate, amountField) {
            const qty = parseFloat(quantity.value) || 0;
            const rt = parseFloat(rate.value) || 0;
            const calculatedAmount = (qty * rt).toFixed(2);
            amountField.value = calculatedAmount;
        }

        // Calculate Amount when Quantity or Rate changes
        if (quantityInput && rateInput && amountInput) {
            quantityInput.addEventListener('input', function() {
                calculateAmount(quantityInput, rateInput, amountInput);
            });
            rateInput.addEventListener('input', function() {
                calculateAmount(quantityInput, rateInput, amountInput);
            });
            // Calculate on page load with existing values
            calculateAmount(quantityInput, rateInput, amountInput);
        }
    });
</script>
@endpush