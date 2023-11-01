 <!-- Responsive Navigation Menu -->
 <div :class="{ 'block': open, 'hidden': !open }" class="hidden border-t border-rose-300 w-full sm:hidden">
     <div class="pt-2 pb-3 space-y-1">
         <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
             {{ __('Home') }}
         </x-responsive-nav-link>
         @foreach ($folders as $folder)
             <x-responsive-nav-link href="{{ route('folder.show', $folder->slug) }}" :active="(request()->route('folder')->slug ?? null) == $folder->slug">
                 {{ $folder->name }}
             </x-responsive-nav-link>
         @endforeach
     </div>
     @auth
         <!-- Responsive Settings Options -->
         <div class="pb-1 border-t border-gray-200 dark:border-gray-600">

             <div class="mt-3 space-y-1">
                 <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                     {{ __('Profile') }}
                 </x-responsive-nav-link>

                 <!-- Authentication -->
                 <form method="POST" action="{{ route('logout') }}">
                     @csrf

                     <x-responsive-nav-link :href="route('logout')"
                         onclick="event.preventDefault();
                                    this.closest('form').submit();">
                         {{ __('Log Out') }}
                     </x-responsive-nav-link>
                 </form>
             </div>
         </div>
     </div>
 @endauth
