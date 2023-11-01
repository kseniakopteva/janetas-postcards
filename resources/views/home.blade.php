{{-- @props(['folder']) --}}

<x-main-layout>
    <x-breadcrumbs />
    <div class="flex justify-start items-center my-4 gap-4">
        <h2 class="text-lg">
            My Collections
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
                        x-on:click.prevent="$dispatch('open-modal', 'new-root-folder')">
                        Add New Section
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        @endauth
    </div>
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10 mb-10 place-content-center mx-4">
        @foreach ($folders as $folder)
            <x-folder :folder="$folder" />
        @endforeach
    </section>
    <div class="flex justify-start items-center my-4 gap-4">
        <h2 class="text-lg">
            Recent Uploads
        </h2>
    </div>

</x-main-layout>
