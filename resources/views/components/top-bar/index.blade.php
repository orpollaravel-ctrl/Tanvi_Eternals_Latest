@props(['layout' => 'side-menu'])
<style>
#pagination-container,#paginationLinks p.text-sm.text-gray-700.leading-5.dark\:text-gray-400, #paginationLinks select, {
	display:none !important;
}
</style>
<!-- BEGIN: Top Bar -->
<div @class([
    'h-[70px] md:h-[65px] z-[51] border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:-mx-0 px-3 md:border-b-0 relative md:fixed md:inset-x-0 md:top-0 sm:px-8 md:px-10 md:pt-10 md:bg-gradient-to-b md:from-slate-100 md:to-transparent dark:md:from-darkmode-700',
    'dark:md:from-darkmode-800' => $layout == 'top-menu',
    "before:content-[''] before:absolute before:h-[65px] before:inset-0 before:top-0 before:mx-7 before:bg-primary/30 before:mt-3 before:rounded-xl before:hidden before:md:block before:dark:bg-darkmode-600/30",
    "after:content-[''] after:absolute after:inset-0 after:h-[65px] after:mx-3 after:bg-primary after:mt-5 after:rounded-xl after:shadow-md after:hidden after:md:block after:dark:bg-darkmode-600",
])>
    <div class="flex h-full items-center">
        <!-- BEGIN: Logo -->
        <a
            href="/"
            @class([
                '-intro-x hidden md:flex',
                'xl:w-[180px]' => $layout == 'side-menu',
                'xl:w-auto' => $layout == 'simple-menu',
                'w-auto' => $layout == 'top-menu',
            ])
        >
            <img
                class="h-7"  style="height: 40px !important;"
                src="{{ Vite::asset('resources/images/tanvi.svg') }}"
                alt="Tanvi"
            />
            <span @class([
                'ml-3 text-lg text-white',
                'hidden xl:block' => $layout == 'side-menu',
                'hidden' => $layout == 'simple-menu',
            ])> 
            </span>
        </a>
        <!-- END: Logo -->
        <!-- BEGIN: Breadcrumb -->
        <x-base.breadcrumb
            @class([
                'h-[45px] md:ml-10 md:border-l border-white/[0.08] dark:border-white/[0.08] mr-auto -intro-x',
                'md:pl-6' => $layout != 'top-menu',
                'md:pl-10' => $layout == 'top-menu',
            ])
            light
        >
            <x-base.breadcrumb.link :index="0">Application</x-base.breadcrumb.link>
            <x-base.breadcrumb.link
                :index="1"
                :active="true"
            >
                Dashboard
            </x-base.breadcrumb.link>
        </x-base.breadcrumb>
        <!-- END: Breadcrumb -->  
        <!-- BEGIN: Account Menu -->
        <x-base.menu>
            <x-base.menu.button class="image-fit zoom-in intro-x block h-8 w-8 scale-110 overflow-hidden rounded-full shadow-lg">
                @if(auth('client')->check() && auth('client')->user()->photo)
                    <img
                        src="{{ url('uploads/client/' . auth('client')->user()->photo) }}"
                        alt="Client Photo"
                    />
                @elseif(auth()->check() && auth()->user()->photo)
                    <img
                        src="{{ url('uploads/user/' . auth()->user()->photo) }}"
                        alt="User Photo"
                    />
                @else
                    <img
                        src="{{ url('uploads/logo.png') }}"
                        alt="Default Photo"
                    />
                @endif
            </x-base.menu.button>
            <x-base.menu.items
                class="relative mt-px w-56 bg-primary/80 text-white before:absolute before:inset-0 before:z-[-1] before:block before:rounded-md before:bg-black">
                <x-base.menu.header class="font-normal">
                    <div class="font-medium">{{ auth()->user()->name ?? '' }}</div>
                </x-base.menu.header>
                <x-base.menu.divider class="bg-white/[0.08]" /> 
                    <x-base.menu.item class="hover:bg-white/5">
                      <form action="{{ auth('client')->check() 
                                ? route('customer.profile') 
                                : route('update-profile') }}" 
                            method="GET">
                            <button type="submit" class="flex items-center w-full text-left">
                                <x-base.lucide class="mr-2 h-4 w-4" icon="User" /> Profile
                            </button>
                        </form>
                    </x-base.menu.item>
                <x-base.menu.item class="hover:bg-white/5">
                    <form action="{{ auth('client')->check() ? route('customer.logout') : route('logout') }}"  method="POST">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left">
                            <x-base.lucide class="mr-2 h-4 w-4" icon="ToggleRight" /> Logout
                        </button>
                    </form>
                </x-base.menu.item>
            </x-base.menu.items>
        </x-base.menu>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->

@once
    @push('scripts')
        @vite('resources/js/components/top-bar/index.js')
    @endpush
@endonce
