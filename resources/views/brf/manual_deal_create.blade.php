@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Manual Deal - Jewelry ERP</title>
@endsection

@section('subcontent')
    <div class="flex mt-10 mb-5 items-center justify-start">
        <a href="{{route('bullion.dashboard')}}"><x-base.button class="mr-2 shadow-md" variant="primary"> <x-base.lucide class="mr-1 h-4 w-4" icon="arrow-left" />Back</x-base.button></a>
    </div>
    <h2 class="intro-y text-lg font-medium">Create Manual Deal</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">

                {{-- Success Message --}}
                @if (session('success_message'))
                    <div class="mb-5 rounded-md border border-success/20 bg-success/10 p-4 text-success">
                        {{ session('success_message') }}
                    </div>
                @endif

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

                <form method="POST" action="{{ route('manual_deal.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Bullion Pending Deals -->
                        <div class="col-span-12">
                            <x-base.form-label>Bullion Pending Deals *</x-base.form-label>
                            <x-base.form-select id="brf" name="brf" required>
                                <option value="0">Select Bullion Deal</option>
                                @if (!empty($brfs))
                                    @foreach ($brfs as $brf)
                                        <option
                                            data-quantity="{{ $brf->pending }}"
                                            data-rate="{{ $brf->rate }}"
                                            value="{{ $brf->id }}"
                                            @if (old('brf') == $brf->id) selected @endif
                                        >
                                            {{ $brf->id }},{{ $brf->bullion->name }},{{ round($brf->pending,3) }}, {{ $brf->rate }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Client Pending Deals -->
                        <div class="col-span-12">
                            <x-base.form-label>Client Pending Deals *</x-base.form-label>
                            <x-base.form-select id="drf" name="drf" required>
                                <option value="0">Select Client Deal</option>
                                @if (!empty($drfs))
                                    @foreach ($drfs as $drf)
                                        <option
                                            data-quantity="{{ $drf->pending }}"
                                            data-rate="{{ $drf->rate }}"
                                            value="{{ $drf->id }}"
                                            @if (old('drf') == $drf->id) selected @endif
                                        >
                                            {{ $drf->id }},{{ $drf->client->name }}, {{ round($drf->pending,3) }},{{ $drf->rate }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        <!-- Loss -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Loss</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                id="amount"
                                name="amount"
                                readonly
                                placeholder=""
                            />
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('manual_deal.create') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Manual Deal
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('third_party_scripts')
   
    <script type="text/javascript">
        $(function() {
            Inputmask("indianns", {
                autoUnmask: true,
                removeMaskOnSubmit: true,
                digits: 2,
                secondaryGroupSize: 2,
            }).mask("amount");
            $('#drf').on('change', function(e) {
                var loss = 0;
                if ($('#brf').val() > 0 && $('#drf').val() > 0) {
                    var brf_qty = $("option:selected", $('#brf')).data('quantity');
                    var drf_qty = $("option:selected", $('#drf')).data('quantity');
                    var brf_rate = $("option:selected", $('#brf')).data('rate');
                    var drf_rate = $("option:selected", $('#drf')).data('rate');
                    var qty = (brf_qty > drf_qty) ? drf_qty : brf_qty;
                    var loss = (qty * drf_rate * 0.10) - (qty * brf_rate * 0.10);
                }
                $('input[name="amount"]').val(loss);
            });
            $('#brf').on('change', function(e) {
                var loss = 0;
                if ($('#brf').val() > 0 && $('#drf').val() > 0) {
                    var brf_qty = $("option:selected", $('#brf')).data('quantity');
                    var drf_qty = $("option:selected", $('#drf')).data('quantity');
                    var brf_rate = $("option:selected", $('#brf')).data('rate');
                    var drf_rate = $("option:selected", $('#drf')).data('rate');
                    var qty = (brf_qty > drf_qty) ? drf_qty : brf_qty;
                    var loss = (qty * drf_rate * 0.10) - (qty * brf_rate * 0.10);
                }
                $('input[name="amount"]').val(loss);
            });
        });
    </script>
@endsection


