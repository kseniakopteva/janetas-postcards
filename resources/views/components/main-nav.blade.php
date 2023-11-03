<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 w-full max-w-7xl mx-auto p-2 pb-0']) }}>
    <nav x-data="{ open: false }" class="flex flex-col md:flex-row justify-between items-center max-w-5xl mx-auto">


        <div class="md:flex-row gap-4 hidden md:flex pb-2 pl-2 xl:pl-0">
            <div>
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-nav-link>
                <span class="text-gray-500 pl-2 hidden md:inline">|</span>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                @foreach ($folders as $folder)
                    <x-nav-link href="{{ route('folder.show', $folder->slug) }}" :active="(request()->route('folder')->slug ?? null) == $folder->slug">
                        {{ $folder->name }}
                    </x-nav-link>
                @endforeach
            </div>
        </div>

        <div class="flex justify-between w-full md:w-auto items-center">
            <!-- Hamburger -->
            <div class="-mr-2 flex items-center md:hidden pb-2">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if (!auth()->check() && !$min && request()->routeIs('home'))
                <a href="{{ route('login') }}"
                    class="pr-2 xl:pr-0 -mt-2 font-semibold text-rose-300 hover:text-rose-500 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-rose-500">Log
                    in</a>
            @endif
        </div>

        @if ($errors->getBag('default')->has('section_name'))
            <?php $show = true; ?>
        @else
            <?php $show = false; ?>
        @endif

        <x-new-root-modal :show="$show" />

        <x-responsive-nav :folders="$folders"></x-responsive-nav>
    </nav>
</div>
