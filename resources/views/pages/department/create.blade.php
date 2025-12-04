@extends('../layouts/' . $layout)

@section('subhead')
    <title>Add Department - Jewelry ERP</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Add New Department</h2>
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

                <form method="POST" action="{{ route('departments.store') }}">
                    @csrf
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Department Name -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Department Name *</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter department name"
                                required
                            />
                        </div>

                        <!-- Department Code -->
                        <div class="col-span-12 sm:col-span-6">
                            <x-base.form-label>Department Code</x-base.form-label>
                            <x-base.form-input
                                type="text"
                                name="code"
                                value="{{ old('code') }}"
                                placeholder="Enter department code"
                            />
                        </div>
                    </div>

                    <div class="mt-5 flex items-center">
                        <a href="{{ route('departments.index') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save Department</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
