@props(['min' => false])
@php

    $folders = \App\Models\Folder::where('parent_id', null)->get();

    $last_image = \App\Models\Image::latest()->first();
    $last = date_format(new DateTime($last_image->created_at), 'd.m.o');
    $last_humans = $last_image->created_at->diffForHumans();
@endphp

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

    <style>
        header {
            background-image: url("data:image/svg+xml,<svg id='patternId' width='100%' height='100%' xmlns='http://www.w3.org/2000/svg'><defs><pattern id='a' patternUnits='userSpaceOnUse' width='30' height='40' patternTransform='scale(2) rotate(0)'><rect x='0' y='0' width='100%' height='100%' fill='hsla(0, 0%, 83%, 1)'/><path d='M1.624 19.09l6.597-1.595a.503.503 0 11.238.98L2.145 20l6.314 1.526a.504.504 0 01-.238.98l-6.597-1.595 3.426 3.426a3.813 3.813 0 005.386 0l1.1-1.1a4.584 4.584 0 000-6.475l-1.1-1.099a3.814 3.814 0 00-5.386 0zM-.911 18.377l-1.595-6.597a.504.504 0 11.98-.237L0 17.856l1.526-6.313a.503.503 0 11.98.237L.911 18.377l3.426-3.426a3.813 3.813 0 000-5.386l-1.1-1.099A4.548 4.548 0 000 7.125a4.547 4.547 0 00-3.238 1.341l-1.099 1.099a3.813 3.813 0 000 5.386zM-11.535 16.763a4.584 4.584 0 000 6.476l1.1 1.099a3.813 3.813 0 005.385 0l3.426-3.426-6.597 1.595a.501.501 0 01-.609-.371.504.504 0 01.372-.609l6.313-1.526-6.313-1.526a.504.504 0 11.237-.98l6.597 1.595-3.426-3.426a3.796 3.796 0 00-2.693-1.113c-.975 0-1.95.37-2.693 1.113zM.911 21.625l1.595 6.597a.504.504 0 11-.98.237L0 22.146l-1.526 6.313a.505.505 0 01-.98-.237l1.595-6.597-3.426 3.426a3.813 3.813 0 000 5.386l1.1 1.099a4.584 4.584 0 006.475 0l1.099-1.099a3.813 3.813 0 000-5.386zM31.624 19.09l6.597-1.595a.503.503 0 11.238.98L32.145 20l6.314 1.526a.504.504 0 01-.238.98l-6.597-1.595 3.426 3.426a3.813 3.813 0 005.386 0l1.1-1.1a4.584 4.584 0 000-6.475l-1.1-1.099a3.814 3.814 0 00-5.386 0zM29.089 18.377l-1.595-6.597a.504.504 0 11.98-.237L30 17.856l1.526-6.313a.503.503 0 11.98.237l-1.595 6.597 3.426-3.426a3.813 3.813 0 000-5.386l-1.1-1.099A4.548 4.548 0 0030 7.125a4.547 4.547 0 00-3.238 1.341l-1.099 1.099a3.813 3.813 0 000 5.386zM18.465 16.763a4.584 4.584 0 000 6.476l1.1 1.099a3.813 3.813 0 005.385 0l3.426-3.426-6.597 1.595a.501.501 0 01-.609-.371.504.504 0 01.372-.609l6.313-1.526-6.313-1.526a.504.504 0 11.237-.98l6.597 1.595-3.426-3.426a3.796 3.796 0 00-2.693-1.113c-.975 0-1.95.37-2.693 1.113zM30.911 21.625l1.595 6.597a.504.504 0 11-.98.237L30 22.146l-1.526 6.313a.505.505 0 01-.98-.237l1.595-6.597-3.426 3.426a3.813 3.813 0 000 5.386l1.1 1.099a4.584 4.584 0 006.475 0l1.099-1.099a3.813 3.813 0 000-5.386zM16.624 39.09l6.597-1.595a.503.503 0 11.238.98L17.145 40l6.314 1.526a.504.504 0 01-.238.98l-6.597-1.595 3.426 3.426a3.813 3.813 0 005.386 0l1.1-1.1a4.584 4.584 0 000-6.475l-1.1-1.099a3.814 3.814 0 00-5.386 0zM14.089 38.377l-1.595-6.597a.504.504 0 11.98-.237L15 37.856l1.526-6.313a.503.503 0 11.98.237l-1.595 6.597 3.426-3.426a3.813 3.813 0 000-5.386l-1.1-1.099A4.548 4.548 0 0015 27.125a4.547 4.547 0 00-3.238 1.341l-1.099 1.099a3.813 3.813 0 000 5.386zM3.465 36.763a4.584 4.584 0 000 6.476l1.1 1.099a3.813 3.813 0 005.385 0l3.426-3.426-6.597 1.595a.501.501 0 01-.609-.371.504.504 0 01.372-.609l6.313-1.526-6.313-1.526a.504.504 0 11.237-.98l6.597 1.595-3.426-3.426a3.796 3.796 0 00-2.693-1.113c-.975 0-1.95.37-2.693 1.113zM15.911 41.625l1.595 6.597a.504.504 0 11-.98.237L15 42.146l-1.526 6.313a.505.505 0 01-.98-.237l1.595-6.597-3.426 3.426a3.813 3.813 0 000 5.386l1.1 1.1a4.584 4.584 0 006.475 0l1.099-1.1a3.813 3.813 0 000-5.386zM16.624-.91l6.597-1.595a.503.503 0 11.238.98L17.145 0l6.314 1.526a.504.504 0 01-.238.98L16.624.912l3.426 3.426a3.813 3.813 0 005.386 0l1.1-1.1a4.584 4.584 0 000-6.475l-1.1-1.099a3.814 3.814 0 00-5.386 0zM14.089-1.623L12.494-8.22a.504.504 0 11.98-.237L15-2.144l1.526-6.313a.503.503 0 11.98.237l-1.595 6.597 3.426-3.426a3.813 3.813 0 000-5.386l-1.1-1.099A4.548 4.548 0 0015-12.875a4.547 4.547 0 00-3.238 1.341l-1.099 1.099a3.813 3.813 0 000 5.386zM3.465-3.237a4.584 4.584 0 000 6.476l1.1 1.099a3.813 3.813 0 005.385 0L13.376.912 6.779 2.507a.501.501 0 01-.609-.371.504.504 0 01.372-.609L12.855.001 6.542-1.525a.504.504 0 11.237-.98L13.376-.91 9.95-4.336a3.796 3.796 0 00-2.693-1.113c-.975 0-1.95.37-2.693 1.113zM15.911 1.625l1.595 6.597a.504.504 0 11-.98.237L15 2.146 13.474 8.46a.505.505 0 01-.98-.237l1.595-6.597-3.426 3.426a3.813 3.813 0 000 5.386l1.1 1.099a4.584 4.584 0 006.475 0l1.099-1.099a3.813 3.813 0 000-5.386z'  stroke-width='1' stroke='none' fill='hsla(306, 26%, 65%, 0.66)'/></pattern></defs><rect width='800%' height='800%' transform='translate(0,0)' fill='url(%23a)'/></svg>")
        }
    </style>
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    @if (!$min)
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
                    {{-- <p>Last Updated: yesterday, 29.10.23</p> --}}
                    <p>Last Updated: <a class="text-gray-700 hover:text-indigo-600 hover:underline"
                            href="{{ route('image.show', ['folder' => $last_image->folder->slug, 'image' => $last_image]) }}">{{ $last_humans }},
                            {{ $last }}</a></p>
                </div>
            </div>
        </header>
    @endif

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

            @if (!$min && auth()->check())
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
            @endif


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

                        <x-primary-button class="ml-3">
                            {{ __('Create new section') }}
                        </x-primary-button>
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
