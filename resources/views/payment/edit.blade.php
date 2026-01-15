@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Payment - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Payment</h2>

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

                <form method="POST" action="{{ route('payments.update', $payment) }}">
                    @method('PUT')
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Bullion Name -->
                        <div class="col-span-12">
                            <x-base.form-label>Bullion Name *</x-base.form-label>
                            <x-base.form-select name="bullion" required>
                                <option value="0">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if ($bullion->id == old('bullion', $payment->bullion_id)) selected @endif>
                                            {{ $bullion->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Date and Transferred By -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Date *</x-base.form-label>
                            <x-base.form-input
                                type="date"
                                name="pay_date"
                                value="{{ old('pay_date', \Carbon\Carbon::parse($payment->pay_date)->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Transferred By *</x-base.form-label>
                            <x-base.form-select name="transferred_by" required>
                                <option value="0">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if ($user->id == old('transferred_by', $payment->transferred_by)) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Payment Mode and Amount -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Payment Mode *</x-base.form-label>
                            <x-base.form-select name="paymentMode" required>
                                <option value="0">Select Any..</option>
                                @if (!empty($paymentModes))
                                    @foreach ($paymentModes as $paymentMode)
                                        <option value="{{ $paymentMode->id }}" @if ($paymentMode->id == old('paymentMode', $payment->payment_mode_id)) selected @endif>
                                            {{ $paymentMode->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Amount *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="amount"
                                id="amount"
                                value="{{ old('amount', $payment->amount) }}"
                                placeholder="Amount"
                                required
                            />
                        </div>

                        <!-- Remark -->
                        <div class="col-span-12">
                            <x-base.form-label for="remark">Remark</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="remark"
                                id="remark"
                                value="{{ old('remark', $payment->remark) }}"
                                placeholder="remark"
                                required
                            />

                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('payments.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Update Payment
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('third_party_scripts')
    <script src="{{ mix('js/mask.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            Inputmask("indianns", {
                autoUnmask: true,
                removeMaskOnSubmit: true,
                digits: 2,
                secondaryGroupSize: 2,
            }).mask("amount");
        });
    </script>
@endsection


