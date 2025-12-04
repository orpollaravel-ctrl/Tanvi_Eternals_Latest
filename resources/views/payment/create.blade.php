@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Payment - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Payment</h2>

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

                <form method="POST" action="{{ route('payments.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Bullion Name -->
                        <div class="col-span-12">
                            <x-base.form-label>Bullion Name *</x-base.form-label>
                            <x-base.form-select name="bullion" required>
                                <option value="0">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if (old('bullion') == $bullion->id) selected @endif>
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
                                value="{{ old('pay_date', now()->format('Y-m-d')) }}"
                                @user readonly @enduser
                                required
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Transferred By *</x-base.form-label>
                            <x-base.form-select name="transferred_by" required>
                                <option value="0">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (old('transferred_by') == $user->id) selected @endif>
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
                                        <option value="{{ $paymentMode->id }}" @if (old('paymentMode') == $paymentMode->id) selected @endif>
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
                                value="{{ old('amount') }}"
                                placeholder="Amount"
                                required
                            />
                            <small id="amt_word" class="text-primary float-right"></small>
                        </div>

                        <!-- Remark -->
                        <div class="col-span-12">
                            <x-base.form-label>Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" rows="3">{{ old('remark') }}</x-base.form-textarea>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('payments.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Payment
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('third_party_scripts')
    <script src="{{ asset('js/mask.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            function NumberToWords() {

                var units = ["", "One", "Two", "Three", "Four", "Five", "Six",
                    "Seven", "Eight", "Nine", "Ten"
                ];
                var teens = ["Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen",
                    "Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty"
                ];
                var tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty",
                    "Seventy", "Eighty", "Ninety"
                ];

                var othersIndian = ["Thousand", "Lakh", "Crore"];

                var othersIntl = ["Thousand", "Million", "Billion", "Trillion"];

                var INDIAN_MODE = "indian";
                var INTERNATIONAL_MODE = "international";
                var currentMode = INDIAN_MODE;

                var getBelowHundred = function(n) {
                    if (n >= 100) {
                        return "greater than or equal to 100";
                    };
                    if (n <= 10) {
                        return units[n];
                    };
                    if (n <= 20) {
                        return teens[n - 10 - 1];
                    };
                    var unit = Math.floor(n % 10);
                    n /= 10;
                    var ten = Math.floor(n % 10);
                    var tenWord = (ten > 0 ? (tens[ten] + " ") : '');
                    var unitWord = (unit > 0 ? units[unit] : '');
                    return tenWord + unitWord;
                };

                var getBelowThousand = function(n) {
                    if (n >= 1000) {
                        return "greater than or equal to 1000";
                    };
                    var word = getBelowHundred(Math.floor(n % 100));

                    n = Math.floor(n / 100);
                    var hun = Math.floor(n % 10);
                    word = (hun > 0 ? (units[hun] + " Hundred ") : '') + word;

                    return word;
                };

                return {
                    numberToWords: function(n) {
                        if (isNaN(n)) {
                            return "Not a number";
                        };

                        var word = '';
                        var val;

                        val = Math.floor(n % 1000);
                        n = Math.floor(n / 1000);

                        word = getBelowThousand(val);

                        if (this.currentMode == INDIAN_MODE) {
                            othersArr = othersIndian;
                            divisor = 100;
                            func = getBelowHundred;
                        } else if (this.currentMode == INTERNATIONAL_MODE) {
                            othersArr = othersIntl;
                            divisor = 1000;
                            func = getBelowThousand;
                        } else {
                            throw "Invalid mode - '" + this.currentMode +
                                "'. Supported modes: " + INDIAN_MODE + "|" +
                                INTERNATIONAL_MODE;
                        };

                        var i = 0;
                        while (n > 0) {
                            if (i == othersArr.length - 1) {
                                word = this.numberToWords(n) + " " + othersArr[i] + " " +
                                    word;
                                break;
                            };
                            val = Math.floor(n % divisor);
                            n = Math.floor(n / divisor);
                            if (val != 0) {
                                word = func(val) + " " + othersArr[i] + " " + word;
                            };
                            i++;
                        };
                        return word;
                    },
                    setMode: function(mode) {
                        if (mode != INDIAN_MODE && mode != INTERNATIONAL_MODE) {
                            throw "Invalid mode specified - '" + mode +
                                "'. Supported modes: " + INDIAN_MODE + "|" +
                                INTERNATIONAL_MODE;
                        };
                        this.currentMode = mode;
                    }
                }
            }
            $('input[name="amount"]').on('change', function(e) {
                var num2words = new NumberToWords();
                num2words.setMode("indian");
                $('#amt_word').text(num2words.numberToWords($(this).val()));
                console.log(num2words.numberToWords($(this).val()));
            });
            Inputmask("indianns", {
                autoUnmask: true,
                removeMaskOnSubmit: true,
                digits: 0,
                secondaryGroupSize: 2,
            }).mask("amount");
        });
    </script>
@endsection


