@props(['folder'])
@php $anc = \App\Models\Folder::find($folder->id)->ancestors->reverse(); @endphp

<x-main-layout class="w-full max-w-7xl mx-auto">
    <p class="space-x-2 text-gray-500 text-sm mt-4">
        <a class="hover:text-indigo-600 hover:underline"
            href="\">Home</a>
        <a>/</a>
        @foreach ($anc as $ancestor)
<a class="hover:text-indigo-600
            hover:underline"
            href="
@php
$ancestor_ancestors = \App\Models\Folder::find($ancestor->id)->ancestorsAndSelf->reverse();
echo '/';
foreach ($ancestor_ancestors as $a) {
    echo  $a->slug . '/' ;
}
// echo $anc->reverse()->first()->slug;
; @endphp

            ">{{ $ancestor->name }}</a>
        <a>/</a>
        @endforeach
        <a class="text-black">{{ $folder->name }}</a>
    </p>
    <div class="flex
            justify-between items-center my-4">

        <h2 class="text-lg">
            {{ $folder->name }}
        </h2>
        <x-dropdown align="right">
            <x-slot name="trigger">
                <x-secondary-button type="submit">...</x-secondary-button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link href="{{ route('image.create', ['folder' => $folder]) }}">Add
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
    </div>
    <section class="grid grid-cols-5 gap-10">
        @foreach ($folder->children as $sub)
            <a href="{{ url()->current() }}/{{ $sub->slug }}">
                {{-- {{ route('folder.show', ['folder' => $sub]) }}"> --}}
                <div class="h-36 flex flex-col justify-center items-center bg-indigo-200 space-y-3">
                    <i class="fa-regular fa-folder-open text-6xl text-white"></i>
                    <p>{{ $sub->name }}</p>
                </div>
            </a>
        @endforeach
        @foreach ($folder->images as $sub)
            <a href="{{ url()->current() }}/{{ $sub->slug }}">
                {{-- {{ route('folder.show', ['folder' => $sub]) }}"> --}}
                <div class="h-36 flex flex-col justify-center items-center bg-indigo-200 space-y-3">
                    <i class="fa-regular fa-folder-open text-6xl text-white"></i>
                    <p>{{ $sub->name }}</p>
                </div>
            </a>
        @endforeach
    </section>

    @if ($errors->getBag('default')->has('folder_name'))
        <?php $show = true; ?>
    @else
        <?php $show = false; ?>
    @endif

    <x-modal name="new-folder" focusable :show="$show">
        <form method="post" action="{{ route('folder.store') }}" class="p-6">
            @csrf
            @method('post')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('New Folder in the "' . $folder->name . '"') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Here you can create a new folder.') }}
            </p>

            <input type="hidden" name="parent" value="{{ $folder->id }}">

            <div class="mt-6">
                <x-input-label for="folder_name" value="{{ __('name') }}" class="sr-only" />

                <x-text-input id="name" name="folder_name" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Name') }}" value="{{ old('folder_name') }}" />

                <x-input-error :messages="$errors->get('folder_name')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Create new folder') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-main-layout>
