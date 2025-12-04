@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Bullion Rate Fix - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Bullion Rate Fix</h2>

    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">

                {{-- Validation Errors --}}
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

                <form method="POST" action="{{ route('brfs.update', $brf) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Date --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="brf_date">Date *</x-base.form-label>
                            <x-base.form-input
                                id="brf_date"
                                type="date"
                                name="brf_date"
                                value="{{ old('brf_date', \Carbon\Carbon::parse($brf->brf_date)->format('Y-m-d')) }}"
                                placeholder="Date"
                                required
                            />
                        </div>

                        {{-- Fixed By --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="fixed_by">Fixed By *</x-base.form-label>
                            <x-base.form-select name="fixed_by">
                                <option value="0">Select Any..</option>
                                @if (!empty($users))
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @if (old('fixed_by', $brf->fixed_by) == $user->id) selected @endif>
                                            {{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        {{-- Bullion Name --}}
                        <div class="col-span-12">
                            <x-base.form-label for="bullion">Bullion Name *</x-base.form-label>
                            <x-base.form-select name="bullion">
                                <option value="0">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if (old('bullion', $brf->bullion_id) == $bullion->id) selected @endif>
                                            {{ $bullion->name }}</option>
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
                                value="{{ old('quantity', $brf->quantity) }}"
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
                                value="{{ old('rate', $brf->rate) }}"
                                placeholder="Rate"
                                required
                            />
                        </div>

                        {{-- Total Value --}}
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label for="amount">Total Value</x-base.form-label>
                            <x-base.form-input
                                id="amount"
                                type="text"
                                name="amount"
                                value="{{ old('amount', $brf->amount) }}"
                                readonly
                                placeholder=""
                            />
                        </div>

                        {{-- Remark --}}
                        <div class="col-span-12">
                            <x-base.form-label for="remark">Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" rows="3">{{ old('remark', $brf->remark) }}</x-base.form-textarea>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('brfs.index') }}">
                            <x-base.button type="button" variant="outline-secondary">
                                Cancel
                            </x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Update Bullion Rate Fix
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


