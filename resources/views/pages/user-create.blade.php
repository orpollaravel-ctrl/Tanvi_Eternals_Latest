@extends('../layouts/' . $layout)

@section('subhead')
    <title>Create User - Midone - Tailwind HTML Admin Template</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Create User</h2>
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

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mt-3">
                        <x-base.form-label>Name</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="John Doe"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Email</x-base.form-label>
                        <x-base.form-input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="john@example.com"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Gender</x-base.form-label>
                        <x-base.form-select name="gender" >
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </x-base.form-select>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Contact Number</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="contact_number"
                            value="{{ old('contact_number') }}"
                            placeholder="09123456789"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Password</x-base.form-label>
                        <x-base.form-input
                            type="password"
                            name="password"
                            placeholder="********"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Confirm Password</x-base.form-label>
                        <x-base.form-input
                            type="password"
                            name="password_confirmation"
                            placeholder="********"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Active Status</x-base.form-label>
                        <input
                            type="checkbox"
                            id="active"
                            name="active"
                            class="mt-2" >
                    </div>
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('users') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Create</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


