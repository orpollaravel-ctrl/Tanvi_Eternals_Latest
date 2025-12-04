@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Client Rate Fix - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Client Rate Fix</h2>
    <div class="mt-5 grid grid-cols-12 gap-8">
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

                <form method="POST" action="{{ route('client-rate-fix.store') }}">
                    @csrf

                    <!-- Transaction No + Client Code -->
                    <div class="grid grid-cols-3 gap-6 mt-6">
                        <div class="mt-3">
                            <x-base.form-label>Serial No*</x-base.form-label>
                            <x-base.form-input 
                                type="text" 
                                name="serial_no" 
                                value="{{ old('serial_no', $nextSerialNo) }}" 
                                class="bg-gray-100 text-right cursor-not-allowed"/>
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Transaction No*</x-base.form-label>
                            <x-base.form-input type="text" name="transaction_no" value="{{ old('transaction_no') }}" placeholder="TI/25-26/0371" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Client Code*</x-base.form-label>
                            <x-base.form-input type="text" name="client_code" value="{{ old('client_code') }}" placeholder="TE110BW" />
                        </div>
                    </div>

                    <!-- Client Name + Jewel Transaction Date -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="mt-3">
                            <x-base.form-label>Client Name*</x-base.form-label>
                            <x-base.form-input type="text" name="client_name" value="{{ old('client_name') }}" placeholder="GOLD LTD." />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Transaction Date*</x-base.form-label>
                            <x-base.form-input type="date" name="transaction_date" value="{{ old('transaction_date') }}" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Sales Person*</x-base.form-label>
                            <x-base.form-input type="text" name="sales_person" value="{{ old('sales_person') }}" placeholder="Party Name" />
                        </div>
                    </div>

                    <!-- Sales Person + Pure Wt -->
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="mt-3">
                            <x-base.form-label>Pure Weight*</x-base.form-label>
                            <x-base.form-input type="number" step="0.001" class="text-right" name="weight" value="{{ old('weight') }}" placeholder="0.000" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Client Rate Cut (Per 10 gram)*</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" name="rate" value="{{ old('rate') }}" placeholder="0.00" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Amount*</x-base.form-label>
                            <x-base.form-input type="number" step="0.01" class="text-right" name="amount" value="{{ old('amount') }}" placeholder="0.00" readonly/>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mt-3">
                        <div class="mt-3">
                            <x-base.form-label>Avg.</x-base.form-label>
                            <x-base.form-input type="number" class="text-right" name="average" value="{{ old('average') }}" placeholder="0.00" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Profit/Loss</x-base.form-label>
                            <x-base.form-input type="number" class="text-right" name="profit_loss" value="{{ old('profit_loss') }}" placeholder="0.000" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('client-rate-fix') }}" class="mr-3">
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
            const rateCutInput = document.querySelector('input[name="rate"]');
            const pureWeightInput = document.querySelector('input[name="weight"]');
            const amountInput = document.querySelector('input[name="amount"]');
        
            function calculateAmount() {
                const rateCut = parseFloat(rateCutInput.value) || 0;
                const pureWeight = parseFloat(pureWeightInput.value) || 0;
                const amount = (rateCut / 10) * pureWeight;
                amountInput.value = amount.toFixed(2);
            }
        
            rateCutInput.addEventListener('input', calculateAmount);
            pureWeightInput.addEventListener('input', calculateAmount);
        });
    </script>
@endpush