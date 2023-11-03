<x-modal name="rename-folder" focusable :show="$show">
    <form method="post" action="{{ route('folder.update') }}" class="p-6">
        @csrf
        @method('patch')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Rename folder "' . $folder->name . '"') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Enter new folder name.') }}
        </p>

        <input type="hidden" name="folder" value="{{ $folder->id }}">

        <div class="mt-6">
            <x-text-input id="name" name="new_folder_name" type="text" class="mt-1 block w-3/4"
                placeholder="{{ __('Name') }}" value="{{ old('new_folder_name') }}" />

            <x-input-error :messages="$errors->get('new_folder_name')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>

            <x-primary-button class="ml-3">
                {{ __('Rename folder') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
