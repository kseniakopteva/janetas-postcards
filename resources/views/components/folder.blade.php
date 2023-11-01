@props(['folder'])

<a href="{{ url()->current() }}/{{ $folder->slug }}" class="inline-block h-52 focus:outline-2 focus:outline-rose-600">
    <div
        class="h-52 hover:text-black/80 relative w-full max-w-[17.6rem] flex flex-col justify-center items-center
         space-y-3 folder hover:shadow-md hover:scale-[1.01] transition p-3">
        <div class="space-y-4 text-center">
            <p class="text-2xl font-bold -mt-4 overflow-hidden text-ellipsis w-52">{{ $folder->name }}
            </p>
            @if ($folder->images->count() > 0 && $folder->children->count() > 0)
                <p class="absolute bottom-4 text-rose-700 left-[calc(50%-5rem)] whitespace-nowrap">
                    <span>{{ $folder->images->count() }} images</span>
                    <span class="mx-2">&#9679;</span>
                    <span>{{ $folder->children->count() }} folders</span>
                </p>
            @elseif ($folder->images->count() > 0 && $folder->children->count() == 0)
                <p class="absolute bottom-4 text-rose-700 left-[calc(50%-2.1rem)] whitespace-nowrap">
                    <span>{{ $folder->images->count() }} images</span>
                </p>
            @elseif ($folder->images->count() == 0 && $folder->children->count() > 0)
                <p class="absolute bottom-4 text-rose-700 left-[calc(50%-2.2rem)] whitespace-nowrap">
                    <span>{{ $folder->children->count() }} folders</span>
                </p>
            @else
                <p class="absolute bottom-4 text-rose-700 left-[calc(50%-2rem)] whitespace-nowrap">
                    <span>(Empty)</span>
                </p>
            @endif
        </div>
    </div>
</a>
