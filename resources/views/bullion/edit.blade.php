@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Bullion - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit Bullion</h2>

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

                <form method="POST" action="{{ route('bullions.update', $bullion->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Name --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="name">Bullion Name *</x-base.form-label>
                            <x-base.form-input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $bullion->name) }}"
                                placeholder="Enter bullion name"
                                required
                            />
                        </div>

                        {{-- Phone --}}
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label for="phone">Phone *</x-base.form-label>
                            <x-base.form-input
                                id="phone"
                                type="text"
                                name="phone"
                                value="{{ old('phone', $bullion->phone) }}"
                                placeholder="Enter mobile number"
                                required
                            />
                        </div>

                        {{-- Status Toggle --}}
                        <div class="col-span-12 mt-2">
                            <x-base.form-label>Status</x-base.form-label>
                            <div class="mt-2">
                                <label class="c-switch c-switch-label c-switch-success">
                                    <input
                                        class="c-switch-input"
                                        name="status"
                                        type="checkbox"
                                        @if($bullion->status) checked @endif
                                    >
                                    <span class="c-switch-slider"
                                          data-checked="On"
                                          data-unchecked="Off">
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('bullions.index') }}">
                            <x-base.button type="button" variant="outline-secondary">
                                Cancel
                            </x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Update Bullion
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


