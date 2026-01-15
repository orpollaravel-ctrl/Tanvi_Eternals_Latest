@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Purchase Party - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Purchase Party</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="box p-5">
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

                <form method="POST" action="{{ route('purchase-parties.update', $purchaseParty->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Party Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Party Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="party_name"
                                value="{{ old('party_name', $purchaseParty->party_name) }}"
                                placeholder="Enter party name"
                                required
                            />
                        </div>

                        <!-- Company Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Company Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="company_name"
                                value="{{ old('company_name', $purchaseParty->company_name) }}"
                                placeholder="Enter company name"
                                required
                            />
                        </div>

                        <!-- GST Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>GST Number *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="gst_number"
                                value="{{ old('gst_number', $purchaseParty->gst_number) }}"
                                placeholder="Enter GST number"
                                maxlength="15"
                                required
                            />
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Mobile Number *</x-base.form-label>
                            <x-base.form-input
                                type="tel"
                                name="mobile_number"
                                value="{{ old('mobile_number', $purchaseParty->mobile_number) }}"
                                placeholder="Enter mobile number"
                                maxlength="15"
                                required
                            />
                        </div>

                        <!-- Email -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Email *</x-base.form-label>
                            <x-base.form-input
                                type="email"
                                name="email"
                                value="{{ old('email', $purchaseParty->email) }}"
                                placeholder="Enter email address"
                                required
                            />
                        </div>

                        <!-- Address -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Address *</x-base.form-label>
                            <x-base.form-textarea
                                name="address"
                                placeholder="Enter complete address"
                                rows="3"
                                required
                            >{{ old('address', $purchaseParty->address) }}</x-base.form-textarea>
                        </div>

                        <!-- Bank Account Number -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Bank Account Number *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="bank_account_number"
                                value="{{ old('bank_account_number', $purchaseParty->bank_account_number) }}"
                                placeholder="Enter bank account number"
                                maxlength="20"
                                required
                            />
                        </div>

                        <!-- IFSC Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>IFSC Code *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="ifsc_code"
                                value="{{ old('ifsc_code', $purchaseParty->ifsc_code) }}"
                                placeholder="Enter IFSC code"
                                maxlength="11"
                                required
                            />
                        </div>
                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('purchase-parties.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Update Party</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
