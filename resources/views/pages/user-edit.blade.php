@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Users - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit User</h2>
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

                <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mt-3">
                        <x-base.form-label>Name</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="John Doe"
                            required
                        />
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Email</x-base.form-label>
                        <x-base.form-input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="john@example.com"
                            required
                        />
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Gender</x-base.form-label>
                        <x-base.form-select name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </x-base.form-select>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Contact Number</x-base.form-label>
                        <x-base.form-input
                            type="text"
                            name="contact_number"
                            value="{{ old('contact_number', $user->contact_number) }}"
                            placeholder="09123456789"
                            required
                        />
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Photo</x-base.form-label>
                        @if($user->photo)
                            <div class="mb-2">
                                <img src="{{ url('uploads/user/' . $user->photo) }}" alt="Current Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @else
                            <div class="mb-2">
                                <img src="{{ url('media-example/no-image.png') }}" alt="Default Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @endif
                        <x-base.form-input
                            type="file"
                            name="photo"
                            accept="image/*"/>
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Password</x-base.form-label>
                        <x-base.form-input
                            type="password"
                            name="password"
                            placeholder="Leave blank to keep current password"
                        />
                    </div>
                    <div class="mt-3">
                        <x-base.form-label>Confirm Password</x-base.form-label>
                        <x-base.form-input
                            type="password"
                            name="password_confirmation"
                            placeholder="Re-enter to change password"
                        />
                    </div>                    
                    <div class="mt-3">
                        <x-base.form-label>Active Status</x-base.form-label>
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    id="active"
                                    name="active"
                                    value="1"
                                    {{ old('active', $user->active) ? 'checked' : '' }}>
                                <span class="ml-2">User is active</span>
                            </label>
                        </div>
                    </div>  
                    <div class="mt-3">
                        <x-base.form-label>Permissions</x-base.form-label>

                        <table class="table border mt-2" style="text-align: center;">
                            <thead>
                                <tr>
                                    <th style="width: 180px;">Name</th>
                                    <th>Permissions</th>
                                </tr>
                            </thead>
                            <tbody>

                              @foreach($permissions->groupBy('group') as $group => $items)
                                <tr class="border-b border-gray-300" style="border: 1px solid black;"> <!-- Add this -->
                                    <td class="font-medium">
                                        {{ ucfirst($group) }}
                                    </td>
                                    <td>
                                        <div class="grid grid-cols-3 gap-y-2">
                                            @foreach($items as $permission)
                                                <label class="flex items-center space-x-2">
                                                    <input 
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        class="form-check-input"
                                                        {{ in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    >
                                                    <span>{{ $permission->label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>

                        @error('permissions')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>                  
                    <div class="mt-5 flex items-center">
                        <a href="{{ route('users') }}" class="mr-3">
                            <x-base.button type="button" variant="outline-secondary">Cancel</x-base.button>
                        </a>
                        <x-base.button type="submit" variant="primary">Save Changes</x-base.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
