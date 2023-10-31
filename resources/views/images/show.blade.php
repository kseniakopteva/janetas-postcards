{{-- @props(['previous']) --}}

<x-main-layout :min="true" class="w-full mx-auto">
    <div class="max-w-7xl">
        <x-breadcrumbs :folder="$image->folder" :active="true" />
    </div>
    <div class="grid grid-cols-12 bg-gray-100 py-6">
        <a class="text-6xl flex justify-start items-center h-full col-span-2 w-full px-3"
            @if (is_null($next)) style="fill: rgb(156 163 175)" @endif
            @if (!is_null($next)) href="{{ route('image.show', ['folder' => $image->folder->slug, 'image' => $next->slug]) }}" @endif>
            {{-- <i class="fa-solid fa-chevron-left"></i> --}}
            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
            </svg>
        </a>


        <img class="col-span-8 h-[calc(100vh-15rem)] mx-auto object-contain"
            src="{{ asset('storage/images/' . $image->file) }}" alt="">

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
    <div class="text-3xl p-3 max-w-5xl mx-auto border-t border-gray-500 flex justify-between">
        <span>{{ $image->slug }}</span>
        {{-- <span>{{ $image->created_at }}</span> --}}
    </div>
</x-main-layout>
