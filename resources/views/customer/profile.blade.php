@extends('../layouts/' . $layout)

@section('subhead')
    <title>Update Profile - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Update Profile</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 lg:col-span-6">
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

                <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mt-3">
                        <x-base.form-label>Name*</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="name"
                            value="{{ old('name', $client->name) }}"
                            placeholder="John Doe"
                            required
                        />
                    </div>
                    
                    <div class="mt-3">
                        <x-base.form-label>Email*</x-base.form-label>
                        <x-base.form-input
                            type="email"
                            name="email"
                            value="{{ old('email', $client->email) }}"
                            placeholder="john@example.com"
                            required
                        />
                    </div>
                    
                    <div class="mt-3">
                        <x-base.form-label>Contact Number*</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="mobile_number"
                            value="{{ old('mobile_number', $client->mobile_number) }}"
                            placeholder="09123456789"
                            required
                        />
                    </div>
                    
                    <div class="mt-3">
                        <x-base.form-label>Photo</x-base.form-label>
                        @if($client->photo)
                            <div class="mb-2">
                                <img src="{{ url('uploads/client/' . $client->photo) }}" alt="Current Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @else
                            <div class="mb-2">
                                <img src="{{ url('uploads/logo.png') }}" alt="Default Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @endif
                        <x-base.form-input
                            type="file"
                            name="photo"
                            accept="image/*"/>
                    </div>
                    
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('customer.dashboard') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Update Profile</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection