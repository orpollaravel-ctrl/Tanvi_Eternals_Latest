@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Dealer Rate Fix - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Dealer Rate Fix</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger">
                        <div class="font-medium">There were some problems with your input.</div>
                        <ul class="mt-2 list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- SUCCESS --}}
                @if (session('success_message'))
                    <div class="mb-5 rounded-md border border-success/20 bg-success/10 p-4 text-success">
                        {{ session('success_message') }}
                    </div>
                @endif

                {{-- ERROR --}}
                @if (Session::has('error_message'))
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger">
                        {{ Session::get('error_message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('drfs.update', $drf->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Date --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="drf_date">Date *</x-base.form-label>
                            <x-base.form-input
                                id="drf_date"
                                type="date"
                                name="drf_date"
                                value="{{ old('drf_date', \Carbon\Carbon::parse($drf->drf_date)->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        {{-- Fixed By --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="fixed_by">Fixed By *</x-base.form-label>
                            <x-base.form-select name="fixed_by">
                                <option value="">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (!empty(old('fixed_by', $drf->fixed_by)) && $user->id == old('fixed_by', $drf->fixed_by)) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        {{-- Dealer --}}
                        <div class="col-span-12">
                            <x-base.form-label for="dealer">Dealer Name *</x-base.form-label>
                            <x-base.form-select name="dealer">
                                <option value="">Select Dealer</option>
                                @if (!empty($dealers))
                                    @foreach ($dealers as $dealer)
                                        <option value="{{ $dealer->id }}" @if (!empty(old('dealer', $drf->dealer_id)) && $dealer->id == old('dealer', $drf->dealer_id)) selected @endif>
                                            {{ $dealer->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        {{-- Quantity --}}
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label for="quantity">Booking Quantity (In Grams) *</x-base.form-label>
                            <x-base.form-input
                                id="quantity"
                                type="text"
                                name="quantity"
                                value="{{ old('quantity', $drf->quantity) }}"
                                placeholder="Quantity"
                                required
                            />
                        </div>

                        {{-- Rate --}}
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label for="rate">Booking Rate (for 10 Grams) *</x-base.form-label>
                            <x-base.form-input
                                id="rate"
                                type="text"
                                name="rate"
                                value="{{ old('rate', $drf->rate) }}"
                                placeholder="Rate"
                                required
                            />
                        </div>

                        {{-- Amount --}}
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label for="amount">Total Value</x-base.form-label>
                            <x-base.form-input
                                id="amount"
                                type="text"
                                name="amount"
                                value="{{ old('amount', $drf->amount) }}"
                                readonly
                                placeholder=""
                            />
                        </div>

                        {{-- Remark --}}
                        <div class="col-span-12">
                            <x-base.form-label for="remark">Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" rows="3">{{ old('remark', $drf->remark) }}</x-base.form-textarea>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('drfs.index') }}">
                            <x-base.button type="button" variant="outline-secondary">
                                Cancel
                            </x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Update Dealer Rate Fix
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


