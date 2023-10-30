{{-- @props(['folders']) --}}
@php $folders = \App\Models\Folder::where('parent_id', null)->get() @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Janeta's Postcards</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/b3479de9e5.js" crossorigin="anonymous"></script>

</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    <header class="h-56 flex items-end">
        {{-- <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
            @if (Route::has('login'))
                <div class="p-6 text-right">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                            in</a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                    @endauth
                </div>
            @endif
        </div> --}}
        <div
            class="bg-gray-200 dark:bg-gray-800 rounded-t-lg p-8
             mx-auto flex flex-col gap-4 sm:flex-row justify-between items-center w-full max-w-5xl">
            <div class="flex items-center space-x-8">
                <img class="rounded-full w-24" {{-- src="https://live.staticflickr.com/7921/buddyicons/169668353@N02_r.jpg?1549821634#169668353@N02" --}}
                    src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/271deea8-e28c-41a3-aaf5-2913f5f48be6/de7834s-6515bd40-8b2c-4dc6-a843-5ac1a95a8b55.jpg?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzI3MWRlZWE4LWUyOGMtNDFhMy1hYWY1LTI5MTNmNWY0OGJlNlwvZGU3ODM0cy02NTE1YmQ0MC04YjJjLTRkYzYtYTg0My01YWMxYTk1YThiNTUuanBnIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.BopkDn1ptIwbmcKHdAOlYHyAOOACXW0Zfgbs0-6BY-E"
                    alt="" width="200" height="200">
                <h1 class="text-2xl">Janeta</h1>
            </div>
            <div>
                <p>Last Updated: yesterday, 29.10.23</p>
            </div>
        </div>
    </header>

    <div class="bg-gray-300 dark:bg-gray-800 w-full p-2">
        <div class="flex flex-col sm:flex-row justify-between items-center max-w-5xl mx-auto">
            <nav class="flex flex-col sm:flex-row gap-4">

                @foreach ($folders as $folder)
                    <x-nav-link href="{{ route('folder.show', $folder->slug) }}" :active="(request()->route('folder')->slug ?? null) == $folder->slug">
                        {{ $folder->name }}
                    </x-nav-link>
                @endforeach

            </nav>
            {{-- <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'new-root-folder')">
                + Add New Section
            </x-secondary-button> --}}





            <x-dropdown align="right">
                <x-slot name="trigger">
                    <x-secondary-button type="submit">...</x-secondary-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link class="cursor-pointer" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'new-root-folder')">
                        Add New Section
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>



            @if ($errors->getBag('default')->has('section_name'))
                <?php $show = true; ?>
            @else
                <?php $show = false; ?>
            @endif

            <x-modal name="new-root-folder" focusable :show="$show">
                <form method="post" action="{{ route('folder.store', ['isSection' => true]) }}" class="p-6">
                    @csrf
                    @method('post')

                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('New Section') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Here you can create a new section.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="section_name" value="{{ __('name') }}" class="sr-only" />

                        <x-text-input id="name" name="section_name" type="text" class="mt-1 block w-3/4"
                            placeholder="{{ __('Name') }}" value="{{ old('section_name') }}" />

                        <x-input-error :messages="$errors->get('section_name')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Close') }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __('Create new section') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>

    <main {{ $attributes }}>
        {{ $slot }}
    </main>


    @if (session()->has('error'))
        <div class="fixed bottom-10 right-10 rounded-lg px-4 py-2 text-center bg-red-200 border border-red-400"
            x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            <p>{{ session('error') }}</p>
        </div>
    @elseif(session()->has('success'))
        <div class="fixed bottom-10 right-10 rounded-lg px-4 py-2 text-center bg-indigo-200 border border-indigo-400"
            x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition>
            <p>{{ session('success') }}</p>
        </div>
    @endif

</body>

</html>
