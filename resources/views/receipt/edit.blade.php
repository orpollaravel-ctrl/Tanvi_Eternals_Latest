@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Metal Receipt - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Metal Receipt</h2>

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

                <form method="POST" action="{{ route('receipts.update', $receipt->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Bullion Name --}}
                        <div class="col-span-12">
                            <x-base.form-label for="bullion">Bullion Name *</x-base.form-label>
                            <x-base.form-select id="bullion" name="bullion" required>
                                <option value="">Select Bullion</option>
                                @if (!empty($bullions))
                                    @foreach ($bullions as $bullion)
                                        <option value="{{ $bullion->id }}" @if (old('bullion', $receipt->bullion_id) == $bullion->id) selected @endif>
                                            {{ $bullion->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </x-base.form-select>
                        </div>

                        {{-- Date --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="receipt_date">Date *</x-base.form-label>
                            <x-base.form-input
                                id="receipt_date"
                                type="date"
                                name="receipt_date"
                                value="{{ old('receipt_date', \Carbon\Carbon::parse($receipt->receipt_date)->format('Y-m-d')) }}"
                                required
                            />
                        </div>

                        {{-- Quantity --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="quantity">Quantity (In Grams) *</x-base.form-label>
                            <x-base.form-input
                                id="quantity"
                                type="text"
                                name="quantity"
                                value="{{ old('quantity', $receipt->quantity) }}"
                                placeholder="Enter quantity"
                                required
                            />
                        </div>

                        {{-- Remark --}}
                        <div class="col-span-12">
                            <x-base.form-label for="remark">Remark</x-base.form-label>
                            <x-base.form-textarea name="remark" rows="3">{{ old('remark', $receipt->remark) }}</x-base.form-textarea>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('receipts.index') }}">
                            <x-base.button type="button" variant="outline-secondary">
                                Cancel
                            </x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Update Metal Receipt
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


