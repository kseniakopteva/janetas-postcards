<section class="overflow-auto relative">
    <header>
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profile Information') }}
            </h2>
            <a href="/"
                class="m-2 absolute top-0 right-0 whitespace-nowrap inline-flex items-center px-4 py-2 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150">
                Back home
            </a>
        </div>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 pr-36">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        <div class="grid grid-cols-2 w-full gap-4">
            <div class="space-y-6 col-span-1 flex flex-col">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                        :value="old('username', $user->username)" required autofocus autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="email" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 dark:focus:ring-offset-gray-800">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4 absolute bottom-0 mx-1">
                    <x-primary-button class="absolute bottom-0 left-0 mb-1">{{ __('Save') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="absolute bottom-3 left-20 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Saved.') }}</p>
                    @endif
                </div>
            </div>
            <div class="mt-6 ml-auto pr-10 md:pr-14">
                <span class="block font-medium text-sm text-neutral-700 dark:text-neutral-300">Profile
                    Image</span>

                <div class="h-40 w-40 md:h-40 md:w-40 lg:h-52 lg:w-52
                 {{-- xl:h-60 xl:w-60 --}}
                 "
                    x-data="previewImage()">
                    <label for="image" class="relative cursor-pointer">
                        <div
                            class=" flex justify-center items-center overflow-hidden h-40 w-40 md:h-40 md:w-40
                            lg:h-52 lg:w-52
                            {{-- xl:h-60 xl:w-60 --}}
                             mt-1 flex-col bg-neutral-300 border border-neutral-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 rounded-md">

                            <img id="imagePreview" x-show="imageUrl" :src="imageUrl"
                                class="object-cover w-full h-full">
                            <div id="imagePlaceholder" x-show="!imageUrl"
                                class="text-neutral-300 flex flex-col items-center w-full h-full"
                                style="background-image: url('{{ asset('storage/' . auth()->user()->image) }}'); background-size: cover; background-position: center center;">
                            </div>

                        </div>
                        <div class="w-full h-full absolute bottom-0 flex items-end pb-4 justify-center">
                            <input style="display: none" class="" type="file" name="image" id="image"
                                @change="fileChosen">

                            <input
                                class="inline-flex items-center px-4 py-2 cursor-pointer
                        bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-500 rounded-md font-semibold text-xs text-neutral-700 dark:text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 dark:focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150"
                                type="button" value="Change..." onclick="document.getElementById('image').click();" />
                        </div>
                    </label>
                </div>
                <script>
                    function previewImage() {
                        return {
                            imageUrl: "",

                            fileChosen(event) {
                                this.fileToDataUrl(event, (src) => (this.imageUrl = src));
                            },

                            fileToDataUrl(event, callback) {
                                if (!event.target.files.length) return;

                                let file = event.target.files[0],
                                    reader = new FileReader();

                                reader.readAsDataURL(file);
                                reader.onload = (e) => callback(e.target.result);


                            },
                        };
                    }
                </script>

                <x-input-error :messages="$errors->get('image')" class="mt-2" />

            </div>
        </div>
    </form>
    <form action="{{ route('remove_image') }}" method="POST" class="float-right mr-[3.1rem] md:mr-16 lg:mr-[5.5rem]">
        @csrf @method('delete')
        <x-secondary-button class="mt-6 mx-1 mb-1 border-red-600 text-red-600" type="submit"
            onclick="return confirm('Are you sure you want to remove current image?')">Remove Image</x-secondary-button>
    </form>
</section>
