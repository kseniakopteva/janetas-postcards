@props(['folder'])

<x-main-layout class="w-full max-w-7xl mx-auto">

    <x-breadcrumbs :folder="$folder" />

    <div class="flex
            justify-between items-center my-4">

        <h2 class="text-lg">
            {{ $folder->name }}
        </h2>

        @if (auth()->check())
            <x-dropdown align="right">
                <x-slot name="trigger">
                    <x-secondary-button type="submit">...</x-secondary-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link class="cursor-pointer" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'add-images')">Add
                        Images</x-dropdown-link>
                    <x-dropdown-link class="cursor-pointer" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'new-folder')">
                        Add Folder
                    </x-dropdown-link>

                    <form action="{{ route('folder.destroy') }}" method="POST">
                        @csrf @method('delete')
                        <input type="hidden" name="id" value="{{ $folder->id }}">
                        <button
                            onclick="return confirm('Are you sure you want to delete folder &quot;{{ $folder->name }}&quot;?')"
                            class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600"">Delete
                            folder</button>
                    </form>
                </x-slot>
            </x-dropdown>
        @endif
    </div>
    <section class="grid grid-cols-4 gap-10 mb-10">
        @foreach ($folder->children as $sub)
            <a href="{{ url()->current() }}/{{ $sub->slug }}">
                <div class="h-52 flex flex-col justify-center items-center bg-indigo-200 space-y-3">
                    <i class=" text-6xl">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <style>
                                svg {
                                    fill: #ffffff
                                }
                            </style>
                            <path
                                d="M384 480h48c11.4 0 21.9-6 27.6-15.9l112-192c5.8-9.9 5.8-22.1 .1-32.1S555.5 224 544 224H144c-11.4 0-21.9 6-27.6 15.9L48 357.1V96c0-8.8 7.2-16 16-16H181.5c4.2 0 8.3 1.7 11.3 4.7l26.5 26.5c21 21 49.5 32.8 79.2 32.8H416c8.8 0 16 7.2 16 16v32h48V160c0-35.3-28.7-64-64-64H298.5c-17 0-33.3-6.7-45.3-18.7L226.7 50.7c-12-12-28.3-18.7-45.3-18.7H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H87.7 384z" />
                        </svg>
                    </i>
                    <p>{{ $sub->name }}</p>
                </div>
            </a>
        @endforeach
        @foreach ($folder->images->reverse() as $image)
            <a href="{{ route('image.show', ['folder' => $folder->slug, 'image' => $image->slug]) }}">
                <div class="">
                    <img class="h-52 w-full object-cover" src="{{ asset('storage/images/' . $image->file) }}"
                        alt="">
                </div>
            </a>
        @endforeach
    </section>

    @php
        if ($errors->getBag('default')->has('folder_name')) {
            $show_folder_modal = true;
        } else {
            $show_folder_modal = false;
        }

        if ($errors->getBag('default')->has('files')) {
            $show_images_modal = true;
        } else {
            $show_images_modal = false;
        }
    @endphp

    <x-new-folder-modal :show="$show_folder_modal" :folder="$folder" />
    <x-new-images-modal :show="$show_images_modal" :folder="$folder" />
</x-main-layout>
