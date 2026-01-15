@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Bullion Purchase - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Bullion Purchase</h2>
    <div class="mt-5 grid grid-cols-8 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
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

                <form method="POST" action="{{ route('bullion-purchase.store') }}">
                    @csrf

                    <div class="grid grid-cols-4 gap-4 mt-6">
                        <div>
                            <x-base.form-label>Serial No*</x-base.form-label>
                            <x-base.form-input 
                                type="text" 
                                name="serial_no" 
                                value="{{ old('serial_no', $nextSerialNo) }}" 
                                readonly 
                                class="bg-gray-100 text-right cursor-not-allowed"/>
                        </div>
                        <div>
                            <x-base.form-label>Transaction No*</x-base.form-label>
                            <x-base.form-input type="text" name="transaction_no" value="{{ old('transaction_no') }}" placeholder="TI/25-26/0371" />
                        </div>
                        <div>
                            <x-base.form-label>Transaction Date*</x-base.form-label>
                            <x-base.form-input type="date" name="transaction_date" value="{{ old('transaction_date') }}" />
                        </div>      
                        <div>
                            <x-base.form-label>Client Name*</x-base.form-label>
                            <x-base.form-input type="text" name="name" value="{{ old('name') }}" placeholder="Name" />
                        </div>                 
                    </div>

                    <!-- Converted Weight + Purchase Rate + Amount -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Weight (Gram)</x-base.form-label>
                            <x-base.form-input type="number" step="0.001" class="text-right" id="converted_weight" name="converted_weight" value="{{ old('converted_weight', 0) }}" placeholder="0.000" />
                        </div>
                        <div>
                            <x-base.form-label>Rate per Gram</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="purchase_rate" name="purchase_rate" value="{{ old('purchase_rate', 0) }}" placeholder="0.00" />
                        </div>
                        <div>
                            <x-base.form-label>Amount</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="amount" name="amount" value="{{ old('amount', 0) }}" placeholder="0.00" readonly />
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('bullion-purchase') }}" class="mr-3">
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
        const convertedWeightInput = document.getElementById('converted_weight');
        const purchaseRateInput = document.getElementById('purchase_rate');
        const amountInput = document.getElementById('amount');

        // Function to calculate amount (converted_weight * purchase_rate)
        function calculateAmount() {
            const weight = parseFloat(convertedWeightInput.value) || 0;
            const rate = parseFloat(purchaseRateInput.value) || 0;
            const calculatedAmount = weight * rate;
            // Format to 2 decimal places, but preserve full precision for calculation
            amountInput.value = calculatedAmount.toFixed(2);
        }

        // Calculate Amount when Converted Weight or Purchase Rate changes
        if (convertedWeightInput && purchaseRateInput && amountInput) {
            convertedWeightInput.addEventListener('input', calculateAmount);
            purchaseRateInput.addEventListener('input', calculateAmount);
            // Calculate on page load with existing values
            calculateAmount();
        }
    });
</script>
@endpush