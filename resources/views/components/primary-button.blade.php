<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' =>
            'inline-flex items-center px-4 py-2 bg-rose-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-rose-700 dark:hover:bg-white focus:bg-rose-700 dark:focus:bg-white active:bg-rose-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150',
    ]) }}>
    {{ $slot }}
</button>
