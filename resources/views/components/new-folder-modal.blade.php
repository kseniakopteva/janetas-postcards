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

            <x-primary-button class="ml-3">
                {{ __('Create new folder') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
