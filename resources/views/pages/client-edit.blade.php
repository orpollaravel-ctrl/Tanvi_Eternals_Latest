@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Client - Midone</title>
@endsection

@section('subcontent')
<h2 class="intro-y mt-10 text-lg font-medium">Edit Client</h2>

<div class="mt-5 grid grid-cols-12 gap-6">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box p-5">
             @if ($errors->any())
                <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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
                        <x-base.form-label>Email*</x-base.form-label>
                        <x-base.form-input
                            name="email"
                            value="{{ old('email', $client->email) }}"
                            placeholder="jhon.deo@example.com" />
                    </div>
                    <div>
                        <x-base.form-label>Client Type *</x-base.form-label>
                        <x-base.form-select name="client_type" required>
                            <option value="Corporate" @selected(old('client_type', $client->client_type)=='Corporate')>
                                Corporate
                            </option>
                            <option value="Job Work" @selected(old('client_type', $client->client_type)=='Job Work')>
                                Job Work
                            </option>
                            <option value="B2B" @selected(old('client_type', $client->client_type)=='B2B')>
                                B2B
                            </option>
                            <option value="SIS" @selected(old('client_type', $client->client_type)=='SIS')>
                                SIS
                            </option>
                        </x-base.form-select>
                    </div>
                     <div>
                        <x-base.form-label>Salesman*</x-base.form-label>
                        <x-base.tom-select name="salesman_id" class="tom-select w-full" required>
                            <option value="">Select Salesman</option>
                            @foreach ($salesman as $emp)
                                <option value="{{ $emp->id }}" @selected(old('salesman_id',$client->salesman_id) == $emp->id)>
                                    {{ $emp->name }}
                                </option>
                            @endforeach
                        </x-base.tom-select>
                     </div> 
                      <div>
                        <x-base.form-label>Mobile Number*</x-base.form-label>
                        <x-base.form-input
                            name="mobile_number"
                            value="{{ old('mobile_number', $client->mobile_number) }}"
                            placeholder="9876543210" />
                    </div>
                     <div>
                        <x-base.form-label>Password*</x-base.form-label>
                        <x-base.form-input
                          type="password"
                            name="password"
                            value=""
                            placeholder="*********" />
                    </div>
                    <div>
                        <x-base.form-label>Confirm Password*</x-base.form-label>
                        <x-base.form-input
                          type="password"
                            name="password_confirmation"
                            value=""
                            placeholder="*********" />
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
