@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Client - Midone</title>
@endsection

@section('subcontent')
<h2 class="intro-y mt-10 text-lg font-medium">Edit Client</h2>

<div class="mt-5 grid grid-cols-12 gap-6">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box p-5">

            <form method="POST" action="{{ route('client.update', $client->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">

                    <div>
                        <x-base.form-label>Client Code*</x-base.form-label>
                        <x-base.form-input
                            name="code"
                            value="{{ old('code', $client->code) }}"
                            placeholder="CLT-001" />
                    </div>

                    <div>
                        <x-base.form-label>Client Name*</x-base.form-label>
                        <x-base.form-input
                            name="name"
                            value="{{ old('name', $client->name) }}"
                            placeholder="Tanvi Jewellers" />
                    </div>

                    <div>
                        <x-base.form-label>Salesman Name*</x-base.form-label>
                        <x-base.form-input
                            name="salesman_name"
                            value="{{ old('salesman_name', $client->salesman_name) }}"
                            placeholder="Ramesh Kumar" />
                    </div>

                    <div>
                        <x-base.form-label>Mobile Number*</x-base.form-label>
                        <x-base.form-input
                            name="mobile_number"
                            value="{{ old('mobile_number', $client->mobile_number) }}"
                            placeholder="9876543210" />
                    </div>

                    <div class="col-span-2">
                        <x-base.form-label>Address 1</x-base.form-label>
                        <x-base.form-input
                            name="address_1"
                            value="{{ old('address_1', $client->address_1) }}"
                            placeholder="Shop No. 12, Main Market" />
                    </div>

                    <div class="col-span-2">
                        <x-base.form-label>Address 2</x-base.form-label>
                        <x-base.form-input
                            name="address_2"
                            value="{{ old('address_2', $client->address_2) }}"
                            placeholder="Near City Mall" />
                    </div>

                    <div class="col-span-2">
                        <x-base.form-label>Address 3</x-base.form-label>
                        <x-base.form-input
                            name="address_3"
                            value="{{ old('address_3', $client->address_3) }}"
                            placeholder="Opp. Bus Stand" />
                    </div>

                    <div>
                        <x-base.form-label>City</x-base.form-label>
                        <x-base.form-input
                            name="city"
                            value="{{ old('city', $client->city) }}"
                            placeholder="Ahmedabad" />
                    </div>

                    <div>
                        <x-base.form-label>State</x-base.form-label>
                        <x-base.form-input
                            name="state"
                            value="{{ old('state', $client->state) }}"
                            placeholder="Gujarat" />
                    </div>

                    <div>
                        <x-base.form-label>Zip Code</x-base.form-label>
                        <x-base.form-input
                            name="zip_code"
                            value="{{ old('zip_code', $client->zip_code) }}"
                            placeholder="380015" />
                    </div>

                </div>

                <div class="mt-5 flex">
                    <a href="{{ route('client.index') }}" class="mr-3">
                        <x-base.button variant="outline-secondary">Cancel</x-base.button>
                    </a>
                    <x-base.button variant="primary" type="submit">Update</x-base.button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
