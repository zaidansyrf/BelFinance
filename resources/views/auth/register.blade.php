<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Create Account</h1>
            <p class="text-gray-600 mt-2">Join us today and get started</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="name"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                    type="text" name="name" :value="old('name')" placeholder="Enter your full name" required
                    autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="email"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                    type="email" name="email" :value="old('email')" placeholder="Enter your email" required
                    autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="password"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                    type="password" name="password" placeholder="Create a password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                    class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="password_confirmation"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                    type="password" name="password_confirmation" placeholder="Confirm your password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex items-center justify-between mt-8">
                <a class="text-sm font-medium text-[#2B7A78] hover:text-[#1a5354] transition duration-150"
                    href="{{ route('login') }}">
                    {{ __('Already have an account?') }}
                </a>

                <button type="submit"
                    class="px-6 py-3 bg-[#2B7A78] hover:bg-[#1a5354] text-white font-medium rounded-lg shadow-md transition duration-150 transform hover:scale-[1.02]">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
