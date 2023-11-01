{{-- @props(['previous']) --}}

<x-main-layout :min="true" class="w-full mx-auto">
    <div class="max-w-7xl">
        <x-breadcrumbs :folder="$image->folder" :active="true" />
    </div>
    <div class="grid grid-cols-12 bg-gray-100 py-6">
        <a class="text-6xl flex justify-start items-center h-full col-span-2 w-full px-3"
            @if (!is_null($next)) href="{{ route('image.show', ['folder' => $image->folder->slug, 'image' => $next->slug]) }}" @endif>
            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                @if (is_null($next)) style="fill: rgb(156 163 175)" @endif
                viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
            </svg>
        </a>

        <div x-data="container" class="col-span-8 mx-auto h-[calc(100vh-20.2rem)]">
            <img class="h-full object-contain" src="{{ asset('storage/images/' . $image->file) }}" alt=""
                {{-- @if (!is_null($slide)) x-transition:enter="transition-transform transition-opacity ease-out duration-300"
                x-transition:leave="transition ease-in duration-300"
                @if ($slide === 'left') x-transition:enter-start="opacity-0 transform translate-x-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-x-2"
                @else
                x-transition:enter-start="opacity-0 transform -translate-x-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-x-2" @endif
                x-show="show" x-init="$nextTick(() => show = true)"@endif x-cloak --}}>
        </div>

        <a class="text-6xl flex justify-end items-center h-full col-span-2 w-full px-3"
            @if (!is_null($previous)) href="{{ route('image.show', ['folder' => $image->folder->slug, 'image' => $previous->slug]) }}" @endif>
            {{-- <i class="fa-solid first-letter:fa-chevron-right"></i> --}}
            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                @if (is_null($previous)) style="fill: rgb(156 163 175)" @endif
                viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->

                <path
                    d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
            </svg>
        </a>

    </div>
    <div class="p-3 max-w-5xl mx-auto border-t-2 border-dashed border-rose-400 flex justify-between items-center">
        <div class="flex justify-start items-center gap-4">
            <span class="text-3xl">{{ $image->name }}</span>@auth
                <x-dropdown align="left">
                    <x-slot name="trigger">
                        {{-- <x-secondary-button type="submit">...</x-secondary-button> --}}
                        <div class="cursor-pointer">
                            <x-svg type="ellipsis" fill="#5c3951" />
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link class="cursor-pointer" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'rename-image')">Rename Image</x-dropdown-link>
                        <form action="{{ route('image.destroy') }}" method="POST">
                            @csrf @method('delete')
                            <input type="hidden" name="image_id" value="{{ $image->id }}">
                            {{-- <input type="hidden" name="folder_id" value="{{ $image->folder->id }}"> --}}
                            <button
                                onclick="return confirm('Are you sure you want to delete image &quot;{{ $image->name }}&quot;?')"
                                class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600"">Delete
                                image</button>
                        </form>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>
        <span class="text-sm">Uploaded on {{ date_format(new DateTime($image->created_at), 'd.m.o') }}</span>
    </div>

    @php
        if ($errors->getBag('default')->has('image_name')) {
            $show_rename_modal = true;
        } else {
            $show_rename_modal = false;
        }

    @endphp
    <x-modal name="rename-image" focusable :show="$show_rename_modal">
        <form method="post" action="{{ route('image.update') }}" class="p-6">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Rename image "' . $image->name . '"') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Enter new image name.') }}
            </p>

            <input type="hidden" name="image" value="{{ $image->id }}">

            <div class="mt-6">
                <x-input-label for="image_name" value="{{ __('image_name') }}" class="sr-only" />

                <x-text-input id="name" name="image_name" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}" value="{{ old('image_name') }}" />

                <x-input-error :messages="$errors->get('image_name')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Rename image') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

</x-main-layout>
