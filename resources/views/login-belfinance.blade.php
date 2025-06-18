<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />



    <form method="POST" action="{{ route('login') }}" class="max-w-md mx-auto mt-1">
        @csrf

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Login</h1>
            <p class="text-lg mt-2 text-center px-4">Silakan login jika anda Admin/Owner Belindo Kitchen</p>
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
       <div class="mb-6 relative">
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

        <div class="relative">
            <x-text-input id="password" placeholder="Enter your password"
                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150 pr-12"
                type="password" name="password" required autocomplete="current-password" />
            <button type="button" id="togglePassword"
                class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-[#2B7A78]">
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>

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

        <!-- Optional: Sign up link
        <div class="mt-6 text-center">
            <span class="text-sm text-gray-600">
                {{ __("Don't have an account?") }}
            </span>
            <a class="text-sm font-medium text-[#2B7A78] hover:text-[#1a5354] transition duration-150"
                href="{{ route('register') }}">
                {{ __('Sign up') }}
            </a>
        </div> -->
    </form>
    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        if (type === 'text') {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.053 10.053 0 011.622-2.792m1.522-1.522A10.05 10.05 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.172 5.233M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/>
            `;
        } else {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    });
</script>

</x-guest-layout>
