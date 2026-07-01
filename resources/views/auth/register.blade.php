<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="text-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Buat Akun Baru</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan isi data diri Anda</p>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Alamat Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:ring-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-500 dark:focus:bg-emerald-500 dark:active:bg-emerald-400 py-2.5 text-base transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/30">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
                    Login di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
