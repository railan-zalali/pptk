<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div class="text-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Selamat Datang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan login ke akun Anda</p>
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-emerald-800 dark:text-emerald-300 font-medium" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-emerald-500 dark:focus:border-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-600 rounded-md shadow-sm transition-colors duration-200"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 dark:bg-gray-900 dark:border-gray-700 dark:checked:bg-emerald-600" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:ring-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-500 dark:focus:bg-emerald-500 dark:active:bg-emerald-400 py-2.5 text-base transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/30">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
