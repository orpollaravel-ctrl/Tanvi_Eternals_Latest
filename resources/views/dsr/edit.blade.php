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
                        <x-base.tom-select name="client_id" id="client_id">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"  data-client-type="{{ $client->client_type }}"
                                    @selected($dsr->client_id == $client->id)>
                                    {{ $client->name }} ({{ $client->mobile_number }})
                                </option>
                            @endforeach
                        </x-base.tom-select>
                    </div> 
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Client Type *</x-base.form-label> 
                        <x-base.form-input type="text" name="client_type" id="client_type" readonly/>
                    </div> 
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>No. of Shops</x-base.form-label>
                        <x-base.form-input type="number" name="no_of_shops"
                            value="{{ $dsr->no_of_shops }}" />
                    </div> 
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Visiting Card Photo</x-base.form-label>
                        <div class="mb-2">
                            <img
                                id="visiting_card_preview"
                                src="{{ $dsr->visiting_card_photo 
                                    ? asset('uploads/dsr/visiting_cards/' . $dsr->visiting_card_photo)
                                    : '' }}"
                                class="rounded border {{ $dsr->visiting_card_photo ? '' : 'hidden' }}"
                                alt="Visiting Card" style="height: 50px;">
                        </div>
                        <x-base.form-input
                            type="file"
                            name="visiting_card_photo"
                            id="visiting_card_input"
                            accept="image/*" />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <x-base.form-label>Shop Photo</x-base.form-label>

                        <div class="mb-2">
                            <img
                                id="shop_photo_preview"
                                src="{{ $dsr->shop_photo 
                                    ? asset('uploads/dsr/shop_photos/' . $dsr->shop_photo)
                                    : '' }}"
                                class="rounded border {{ $dsr->shop_photo ? '' : 'hidden' }}"
                                alt="Shop Photo" style="height: 50px;">
                        </div>

                        <x-base.form-input
                            type="file"
                            name="shop_photo"
                            id="shop_photo_input"
                            accept="image/*"
                        />
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const clientSelect = document.getElementById('client_id');
            const clientTypeInput = document.getElementById('client_type');

            function setClientType() {
                const option = clientSelect.options[clientSelect.selectedIndex];
                clientTypeInput.value = option?.dataset.clientType || '';
            }

            clientSelect.addEventListener('change', setClientType);
            setClientType();  



            function previewImage(inputId, previewId) {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);

                input.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                        };

                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }

            previewImage('visiting_card_input', 'visiting_card_preview');
            previewImage('shop_photo_input', 'shop_photo_preview');
        });
    </script>
@endpush
