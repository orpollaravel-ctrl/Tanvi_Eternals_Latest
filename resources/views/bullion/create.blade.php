@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Bullion - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Bullion</h2>

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

                <form method="POST" action="{{ route('bullions.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">
                        
                        <!-- Name -->
                        <div class="col-span-12 sm:col-span-8">
                            <x-base.form-label>Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter bullion name"
                                required
                            />
                        </div>

                        <!-- Phone -->
                        <div class="col-span-12 sm:col-span-4">
                            <x-base.form-label>Phone *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Enter phone number"
                                required
                            />
                        </div>

                        <!-- Address -->
                        <div class="col-span-12">
                            <x-base.form-label>Address</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Enter address"
                            />
                        </div>

                        <!-- Status -->
                        <div class="col-span-12 mt-3">
                            <x-base.form-label>Status</x-base.form-label>
                            <div class="mt-2">
                                <label class="form-switch">
                                    <input type="checkbox" name="status" checked>
                                    <i class="form-icon"></i> Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('bullions.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">
                            Add Bullion
                        </x-base.button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


