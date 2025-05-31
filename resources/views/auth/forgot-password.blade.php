<x-guest-layout>
    <div class="max-w-md mx-auto my-12">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Reset Your Password</h1>
            <div class="mt-4 text-sm text-gray-600">
                {{ __('Forgot your password? No problem. Just enter your email address and we\'ll send you a link to reset it.') }}
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 mb-1" />
                <x-text-input id="email"
                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition duration-150"
                    type="email" name="email" :value="old('email')" placeholder="Enter your email address" required
                    autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}"
                    class="text-sm font-medium text-[#2B7A78] hover:text-[#1a5354] transition duration-150">
                    {{ __('Back to login') }}
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-[#2B7A78] hover:bg-[#1a5354] text-white font-medium rounded-lg shadow-md transition duration-150 transform hover:scale-[1.02]">
                    {{ __('Send Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
