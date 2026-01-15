@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Client Rate Fix - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Client Rate Fix</h2>

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

                <form method="POST" action="{{ route('drfs.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Date -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="drf_date"
                                value="{{ old('drf_date', now()->format('Y-m-d')) }}"
                                @user readonly @enduser
                                required
                            />
                        </div>

                        <!-- Fixed By -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Fixed By *</x-base.form-label>
                            <x-base.tom-select name="fixed_by">
                                <option value="">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (!empty(old('fixed_by')) && $user->id == old('fixed_by')) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.tom-select>
                        </div>

                        <!-- Client -->
                        <div class="col-span-12">
                            <x-base.form-label>Client *</x-base.form-label>
                            <x-base.tom-select name="client" required>
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name .'('. $client->code .')' }}</option>
                                @endforeach
                            </x-base.tom-select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Booking Quantity (In Grams) *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="quantity"
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
                                value="{{ old('rate') }}"
                                placeholder="Rate"
                                required
                            />
                        </div>

                        <!-- Amount -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Total Value</x-base.form-label>
                            <x-base.form-input
                                type="text"
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
                        <a href="{{ route('drfs.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Client Rate Fix
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@section('third_party_scripts')
<script>
$(function() {
    $('input[name="rate"], input[name="quantity"]').on('input', function() {
        var rate = parseFloat($('input[name="rate"]').val()) || 0;
        var quantity = parseFloat($('input[name="quantity"]').val()) || 0;
        var amount = rate * quantity * 0.10;
        $('input[name="amount"]').val(amount.toFixed(2));
    });
});
</script>
@endsection


