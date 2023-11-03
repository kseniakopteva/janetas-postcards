@props(['folder', 'edit' => false])

<x-main-layout class="w-full max-w-7xl mx-auto">
    <x-breadcrumbs :folder="$folder" />


    <div class="flex justify-between my-4">
        <div class="flex justify-start items-center gap-4">

            <h2 class="text-lg">
                {{ $folder->name }}
            </h2>

            @auth
                <x-dropdown align="left">
                    <x-slot name="trigger">
                        {{-- <x-secondary-button type="submit">...</x-secondary-button> --}}
                        <div class="cursor-pointer">
                            <x-svg type="ellipsis" fill="#5c3951" />
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link class="cursor-pointer" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-images'); document.getElementById('addImagesForm').reset()">Add
                            Images</x-dropdown-link>
                        <x-dropdown-link class="cursor-pointer" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'new-folder')">
                            Add Folder
                        </x-dropdown-link>
                        <x-dropdown-link class="cursor-pointer" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'rename-folder')">Rename Folder</x-dropdown-link>

                        <form action="{{ route('images.destroy') }}" method="POST">
                            @csrf @method('delete')
                            <input type="hidden" name="id" value="{{ $folder->id }}">
                            <button
                                class="block w-full px-4 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:bg-neutral-100 dark:focus:bg-neutral-800 transition duration-150 ease-in-out text-red-400 hover:text-red-600"">Delete
                                images</button>
                        </form>

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
            @endauth
        </div>

    </div>
    <form action="{{ route('images.mass.destroy') }}" method="POST" class="relative" enctype="multipart/form-data">
        <input type="hidden" name="folder" value="{{ $folder->id }}">
        @csrf @method('delete')
        @if ($edit)
            <div class="flex space-x-4 absolute -top-12 right-0 items-center">
                {{-- <button type="button" onClick="toggle(this)">Select all</button> --}}
                <input type="checkbox" id="all" onClick="toggle(this)" class="hidden" />
                <label for="all" style="user-select: none" class="hover:text-rose-600 cursor-pointer">Select
                    all</label>

                <a href="{{ route('folder.show', ['folder' => $folder]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Cancel</a>
                <x-danger-button onclick="return confirm('Are you sure you want to delete these images?')">Delete
                    images</x-danger-button>
            </div>
        @endif
        <section class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 mb-10 mx-4 place-content-center">

            @foreach ($folder->children as $sub)
                <x-folder :folder="$sub" />
            @endforeach
            @foreach ($folder->images->reverse() as $image)
                <a @if (!$edit) href="{{ route('image.show', ['folder' => $folder->slug, 'image' => $image->slug]) }}" @endif
                    class="focus:outline-2 focus:outline-rose-600 relative">
                    <img class="h-52 w-full object-cover rounded-md shadow-md hover:shadow-md hover:scale-[1.01] transition"
                        src="{{ asset('storage/images/' . $image->file) }}" alt="">
                    @if ($edit)
                        <div class="absolute bg-rose-900/50 top-0 left-0 w-full h-full cursor-default rounded-md"
                            onclick="document.querySelector('{{ '#image-' . $image->id }}').checked = !document.querySelector('{{ '#image-' . $image->id }}').checked">
                        </div>
                        <input
                            class="absolute top-2 right-2 border-2 border-rose-600 bg-rose-200 checked:bg-rose-700 rounded-md p-4"
                            type="checkbox" name="images[]" id="{{ 'image-' . $image->id }}"
                            value="{{ $image->id }}">
                    @endif
                </a>
            @endforeach
        </section>
    </form>

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

        if ($errors->getBag('default')->has('new_folder_name')) {
            $show_rename_folder_modal = true;
        } else {
            $show_rename_folder_modal = false;
        }
    @endphp

    <x-new-folder-modal :show="$show_folder_modal" :folder="$folder" />
    <x-new-images-modal :show="$show_images_modal" :folder="$folder" />
    <x-rename-folder-modal :show="$show_rename_folder_modal" :folder="$folder" />

    <script>
        function toggle(source) {
            checkboxes = document.getElementsByName('images[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</x-main-layout>
