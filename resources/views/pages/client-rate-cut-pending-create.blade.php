@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Client Rate Cut Pending - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Client Rate Cut Pending</h2>
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

                <form method="POST" action="{{ route('client-rate-cut-pending.store') }}">
                    @csrf

                    <!-- Invoice No + Client Code -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div>
                            <x-base.form-label>Invoice No*</x-base.form-label>
                            <x-base.form-input type="text" name="invoice_no" value="{{ old('invoice_no') }}" placeholder="Invoice No" />
                        </div>
                        <div>
                            <x-base.form-label>Client Code</x-base.form-label>
                            <x-base.form-input type="text" name="client_code" value="{{ old('client_code') }}" placeholder="Client Code" />
                        </div>
                    </div>

                    <!-- Client Name + Transaction Date -->
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Client Name*</x-base.form-label>
                            <x-base.form-input type="text" name="client_name" value="{{ old('client_name') }}" placeholder="Client Name" />
                        </div>
                        <div>
                            <x-base.form-label>Transaction Date*</x-base.form-label>
                            <x-base.form-input type="date" name="transaction_date" value="{{ old('transaction_date') }}" />
                        </div>
                    </div>

                    <!-- Sales Person + Transaction No -->
                    <div class="grid grid-cols-2 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Sales Person*</x-base.form-label>
                            <x-base.form-input type="text" name="sales_person" value="{{ old('sales_person') }}" placeholder="Sales Person" />
                        </div>
                        <div>
                            <x-base.form-label>Transaction No</x-base.form-label>
                            <x-base.form-input type="text" name="transaction_no" value="{{ old('transaction_no') }}" placeholder="Transaction No" />
                        </div>
                    </div>

                    <!-- Pure Weight + Sale Rate + Amount -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Pure Weight*</x-base.form-label>
                            <x-base.form-input type="number" step="0.001" class="text-right" id="pure_weight" name="pure_weight" value="{{ old('pure_weight', 0) }}" placeholder="0.000" />
                        </div>
                        <div>
                            <x-base.form-label>Sale Rate*</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="sale_rate" name="sale_rate" value="{{ old('sale_rate', 0) }}" placeholder="0.00" />
                        </div>
                        <div>
                            <x-base.form-label>Amount</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="amount" name="amount" value="{{ old('amount', 0) }}" placeholder="0.00" readonly />
                        </div>
                    </div>

                    <!-- Rate Cut + Amt + Diff Amt -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div>
                            <x-base.form-label>Rate Cut</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="rate_cut" name="rate_cut" value="{{ old('rate_cut', 0) }}" placeholder="0.00" />
                        </div>
                        <div>
                            <x-base.form-label>Amt</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="amt" name="amt" value="{{ old('amt', 0) }}" placeholder="0.00" />
                        </div>
                        <div>
                            <x-base.form-label>Different Amt.</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" id="diff_amt" name="diff_amt" value="{{ old('diff_amt', 0) }}" placeholder="0.00" readonly />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('client-rate-cut-pending') }}" class="mr-3">
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
        const pureWeightInput = document.getElementById('pure_weight');
        const saleRateInput = document.getElementById('sale_rate');
        const amountInput = document.getElementById('amount');
        const rateCutInput = document.getElementById('rate_cut');
        const amtInput = document.getElementById('amt');
        const diffAmtInput = document.getElementById('diff_amt');
    
        // 🔹 Calculate amount (pure_weight * sale_rate)
        function calculateAmount() {
            const weight = parseFloat(pureWeightInput.value) || 0;
            const rate = parseFloat(saleRateInput.value) || 0;
            const calculatedAmount = (rate/10*weight).toFixed(2);
            amountInput.value = calculatedAmount;
        }
    
        // 🔹 Calculate amt (rate_cut * pure_weight)
        function calculateAmt() {
            const weight = parseFloat(pureWeightInput.value) || 0;
            const rateCut = parseFloat(rateCutInput.value) || 0;
            const calculatedAmt = (rateCut/10 * weight).toFixed(2);
            amtInput.value = calculatedAmt;
        }
    
        function calculateDiffAmt() {
            const amount = parseFloat(amountInput.value) || 0;
            const amt = parseFloat(amtInput.value) || 0;
            const diffAmt = (amt - amount).toFixed(2);
            diffAmtInput.value = diffAmt;
        }
    
        // 🔹 Combined recalculation
        function recalcAll() {
            calculateAmount();
            calculateAmt();
            calculateDiffAmt();
        }
    
        // Attach event listeners
        [pureWeightInput, saleRateInput, rateCutInput].forEach(input => {
            if (input) input.addEventListener('input', recalcAll);
        });
    
        // Initial calculation on page load
        recalcAll();
    });
</script>
@endpush

