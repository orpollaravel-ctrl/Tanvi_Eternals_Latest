@extends('../layouts/' . $layout)

@section('subhead')
    <title>Edit Users - Tanvi Eternals</title>
@endsection

@section('subcontent')
    <h2 class="intro-y mt-10 text-lg font-medium">Edit User</h2>
    <div class="mt-5 grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12">
            <div class="box p-8">
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
                    <div class="mt-6">
                        <x-base.form-label class="text-lg font-semibold mb-4">User Permissions</x-base.form-label>

                        <div class="space-y-6 mt-4">
                            @foreach($permissions->groupBy('group') as $group => $items)
                                <div class="border-2 border-gray-800 rounded-lg bg-white">
                                    <div class="bg-primary text-white px-4 py-3 border-b-2 border-gray-800">
                                        <h3 class="font-bold text-lg">{{ ucfirst($group) }} Group</h3>
                                    </div>
                                    <div class="p-4">
                                        @php
                                            $moduleGroups = collect($items)->groupBy(function($permission) {
                                                $parts = explode('-', $permission->name);
                                                return count($parts) > 1 ? $parts[1] : 'general';
                                            });
                                        @endphp
                                        
                                        <div class="flex gap-4 flex-wrap">
                                            @foreach($moduleGroups as $module => $modulePermissions)
                                                <div class="border-2 border-gray-600 rounded bg-gray-50 w-48 flex-shrink-0">
                                                    <div class="bg-gray-200 px-3 py-2 border-b-2 border-gray-600">
                                                        <h4 class="font-semibold text-gray-800">{{ ucfirst($module) }}</h4>
                                                    </div>
                                                    <div class="p-3 space-y-1">
                                                        @foreach($modulePermissions as $permission)
                                                            <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                                                <input 
                                                                    type="checkbox"
                                                                    name="permissions[]"
                                                                    value="{{ $permission->id }}"
                                                                    class="w-4 h-4 text-primary border-gray-400 rounded"
                                                                    {{ in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                                                >
                                                                <span class="text-sm text-gray-700">{{ $permission->label }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('permissions')
                            <div class="mt-3 text-danger font-medium">{{ $message }}</div>
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
