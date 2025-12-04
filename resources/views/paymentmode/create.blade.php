@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Payment Mode - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Payment Mode</h2>

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

                {{-- SUCCESS MESSAGE --}}
                @if (session('success_message'))
                    <div class="mb-5 rounded-md border border-success/20 bg-success/10 p-4 text-success dark:border-success/30">
                        {{ session('success_message') }}
                    </div>
                @endif

                {{-- ERROR MESSAGE --}}
                @if (Session::has('error_message'))
                    <div class="mb-5 rounded-md border border-danger/20 bg-danger/10 p-4 text-danger dark:border-danger/30">
                        {{ Session::get('error_message') }}
                    </div>
                @endif


                <form method="POST" action="{{ route('paymentmodes.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        <!-- Payment Mode Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Payment Mode Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter payment mode name"
                                required
                            />
                        </div>

                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('paymentmodes.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>

                        <x-base.button type="submit" variant="primary">Save Payment Mode</x-base.button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


