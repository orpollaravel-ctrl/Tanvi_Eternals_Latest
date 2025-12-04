@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create Client - Midone</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create Client</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="box p-5">

                {{-- Error Handling --}}
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

                <form method="POST" action="{{ route('client.store') }}">
                    @csrf

                    <!-- Transaction No + Client Code -->
                    <div class="grid grid-cols-2 gap-6 mt-6">
                        <div class="mt-3">
                            <x-base.form-label>Client Code*</x-base.form-label>
                            <x-base.form-input type="text" name="code" value="{{ old('code') }}" placeholder="TE110BW" />
                        </div>
                        <div class="mt-3">
                            <x-base.form-label>Client Name*</x-base.form-label>
                            <x-base.form-input type="text" name="name" value="{{ old('name') }}" placeholder="GOLD LTD." />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('client.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection