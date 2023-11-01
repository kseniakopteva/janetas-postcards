<x-modal name="add-images" focusable :show="$show">
    <div class="p-6">
        <h2 class="text-lg mb-6">Add Images to "{{ $folder->name }}"</h2>
        <form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="folder" value="{{ $folder->id }}">
            <input
                class="
                 block rounded-md text-rose-500 p-6 border border-gray-300 shadow-sm w-full cursor-pointer hover:bg-gray-50
 focus:bg-gray-50 active:bg-gray-50  focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition ease-in-out duration-150
                "
                type="file" name="files[]" multiple class="form-control @error('files') is-invalid @enderror"
                required>
            <x-input-error :messages="$errors->get('files')" class="mt-2" />

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Add Images') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-modal>
