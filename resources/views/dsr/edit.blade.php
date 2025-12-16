@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit DSR - Tanvi Eternals</title>
@endsection

@section('subcontent')
<h2 class="intro-y mt-10 text-lg font-medium">Edit DSR</h2>

<div class="mt-5 grid grid-cols-12 gap-6">
    <div class="intro-y col-span-12 lg:col-span-8">
        <div class="box p-5">
            <form method="POST" action="{{ route('dsr.update', $dsr->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-12 gap-4">

                    <div class="col-span-12">
                        <x-base.form-label>Customer *</x-base.form-label>
                        <x-base.tom-select name="client_id">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    @selected($dsr->client_id == $client->id)>
                                    {{ $client->name }} ({{ $client->mobile_number }})
                                </option>
                            @endforeach
                        </x-base.tom-select>
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Client Type *</x-base.form-label>
                        <x-base.tom-select name="client_type">
                            @foreach(['SIS','Wholesale','Job Work','Counter Sale Customer'] as $type)
                                <option value="{{ $type }}"
                                    @selected($dsr->client_type == $type)>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </x-base.tom-select>
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>No. of Shops</x-base.form-label>
                        <x-base.form-input type="number" name="no_of_shops"
                            value="{{ $dsr->no_of_shops }}" />
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Visiting Card Photo</x-base.form-label>
                        <x-base.form-input type="file" name="visiting_card_photo" />
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Shop Photo</x-base.form-label>
                        <x-base.form-input type="file" name="shop_photo" />
                    </div>
                </div>

                <div class="mt-5">
                    <x-base.button variant="primary">Update DSR</x-base.button>
                    <a href="{{ route('dsr.index') }}" class="ml-3">
                        <x-base.button variant="outline-secondary">Cancel</x-base.button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
