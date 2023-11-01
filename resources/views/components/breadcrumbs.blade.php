@props(['folder' => null, 'active' => false])
@if (is_null($folder))
    <div class="text-gray-500 text-sm mb-4">
        <a class="text-black">Home</a>
    </div>
@else
    @php $ancestors = \App\Models\Folder::find($folder->id)->ancestors->reverse(); @endphp
    <div {{ $attributes->merge(['class' => 'text-gray-500 text-sm mb-4']) }}>

        <p class="space-x-2 ">
            <a class="hover:text-rose-600 hover:underline focus:outline-2 focus:outline-rose-600"
                href="\">Home</a>
        <a>/</a>

{{-- For each ancestor of this folder we print links --}}

        @foreach ($ancestors as $ancestor)
<a class="hover:text-rose-600
                hover:underline focus:outline-2 focus:outline-rose-600"
                href="
@php
$ancestor_ancestors = \App\Models\Folder::find($ancestor->id)->ancestorsAndSelf->reverse();
echo '/';
foreach ($ancestor_ancestors as $a) {
    echo  $a->slug . '/' ;
} @endphp

">{{ $ancestor->name }}</a>
            <a>/</a>
@endforeach


@if ($active)
    <a href="

        @php
$ancestors = \App\Models\Folder::find($folder->id)->ancestors->reverse();
echo '/';
foreach ($ancestors as $a) {
    echo  $a->slug . '/' ;
}
echo $folder->slug; @endphp

        "
        class="hover:text-rose-600 hover:underline focus:outline-2 focus:outline-rose-600">{{ $folder->name }}</a>
@else
    <a class="text-black">{{ $folder->name }}</a>
@endif
</p>

</div>
@endif
