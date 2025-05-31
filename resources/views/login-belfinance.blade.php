<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />



    <form method="POST" action="{{ route('login') }}" class="max-w-md mx-auto mt-1">
        @csrf

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Login Account</h1>
        </div>
        <!-- Name Field -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('Username') }}
            </label>
            <x-text-input id="name" placeholder="Enter your username"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password Field -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    {{ __('Password') }}
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-[#2B7A78] hover:text-[#1a5354] font-medium"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <x-text-input id="password" placeholder="Enter your password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mb-6">
            <input id="remember_me" type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">
                {{ __('Remember me') }}
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
            class="w-full py-3 px-4 bg-[#2B7A78] hover:bg-[#1a5354] text-white font-medium rounded-lg shadow-md transition duration-150 ease-in-out transform hover:scale-[1.02]">
            {{ __('Log in') }}
        </button>

        <!-- Optional: Sign up link -->
        <div class="mt-6 text-center">
            <span class="text-sm text-gray-600">
                {{ __("Don't have an account?") }}
            </span>
            <a class="text-sm font-medium text-[#2B7A78] hover:text-[#1a5354] transition duration-150"
                href="{{ route('register') }}">
                {{ __('Sign up') }}
            </a>
        </div>
    </form>
</x-guest-layout>
