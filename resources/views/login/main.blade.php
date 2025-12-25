@extends('../layouts/' . $layout)

@section('head')
    <title>Login - Tanvi</title>
@endsection

@section('content')
    <div @class([
        '-m-3 sm:-mx-8 p-3 sm:px-8 relative h-screen lg:overflow-hidden bg-primary xl:bg-white dark:bg-darkmode-800 xl:dark:bg-darkmode-600',
        'before:hidden before:xl:block before:content-[\'\'] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[13%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-4.5deg] before:bg-primary/20 before:rounded-[100%] before:dark:bg-darkmode-400',
        'after:hidden after:xl:block after:content-[\'\'] after:w-[57%] after:-mt-[20%] after:-mb-[13%] after:-ml-[13%] after:absolute after:inset-y-0 before:left-0 before:transform before:rotate-[-4.5deg] after:bg-primary after:rounded-[100%] after:dark:bg-darkmode-700',
    ])>
        <div class="container relative z-10 sm:px-10">
            <div class="block grid-cols-2 gap-4 xl:grid">
                <!-- BEGIN: Login Info -->
                <div class="hidden min-h-screen flex-col xl:flex">
                    <div class="my-auto">
                        <img class="-intro-x -mt-16 w-1/2" width="150"
                            src="{{ Vite::asset('resources/images/smart.svg') }}" />
                        <div class="-intro-x mt-10 text-4xl font-medium leading-tight text-white">
                            Smart ERP for Smarter <br />
                            Jewellery Manufacturing.
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400">
                            Sign In to your Account
                        </div>
                    </div>
                </div>
                <!-- END: Login Info -->

                <!-- BEGIN: Login Form -->
                <div class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0">
                    <div class="mx-auto my-auto w-full rounded-md bg-white text-center border p-8 shadow-xl sm:w-3/4 lg:w-2/4 xl:ml-20 xl:w-auto">
                        <div class="flex items-center justify-center bg-gray-50">
                            <div class="w-full max-w-md">
                                <img class="mx-auto w-1/2" src="{{ Vite::asset('resources/images/logo.svg') }}" alt="Sign In">
                            </div>
                        </div>
                        <h2 class="intro-x text-center text-2xl font-bold xl:text-3xl mt-3"> Sign In </h2>
                        <div class="intro-x mt-2 text-center text-slate-400 xl:hidden">
                            Smart ERP for Smarter Jewellery Manufacturing.
                        </div>
                        <div class="intro-x mt-8">
                            <form method="POST" action="{{ route('login.check') }}">
                                @csrf
                                <x-base.form-input class="intro-x login__input block min-w-full px-4 py-3 xl:min-w-[350px]"
                                    id="email" name="email" type="email" value="{{ old('email') }}"
                                    placeholder="Email" autocomplete="email" />
                                @error('email')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <div class="intro-x mt-4 relative">
                                    <x-base.form-input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="intro-x login__input block min-w-full px-4 py-3 pr-12 xl:min-w-[350px]"
                                        placeholder="Password"
                                        autocomplete="current-password"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword()"
                                        class="absolute right-3 top-1/2 z-20 -translate-y-1/2 text-slate-500 hover:text-primary" style="
                                            top: 25px;
                                            z-index: 111;
                                            right: 12px;
                                        ">
                                        <span id="eye-open">
                                            <x-base.lucide class="h-5 w-5" icon="Eye" />
                                        </span>
                                        <span id="eye-closed" class="hidden">
                                            <x-base.lucide class="h-5 w-5" icon="EyeOff" />
                                        </span>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                                <div class="intro-x mt-5 text-center xl:mt-8">
                                    <x-base.button class="w-full px-4 py-3 align-top xl:mr-3 xl:w-32" type="submit"
                                        variant="primary">
                                        Login
                                    </x-base.button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>    
@endpush
