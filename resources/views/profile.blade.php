@section('title', 'Profile Settings')
<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="flex">
            <!-- Sidebar Placeholder -->

            <!-- Main Content -->
            <div class="flex-1 overflow-y-auto">
                <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
                    <!-- Page Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                                <p class="mt-2 text-sm text-gray-600">Manage your account information and security
                                    settings</p>
                            </div>

                            <div>
                                @if (auth()->user()->role === 'owner')
                                    <a href="{{ route('owner-beranda') }}"
                                        class="text-sm bg-[#468585] hover:bg-[#468585] text-white font-medium px-4 py-2 rounded transition">
                                        kembali
                                    </a>
                                @elseif(auth()->user()->role === 'keuangan')
                                    <a href="{{ route('dashboard') }}"
                                        class="text-sm bg-[#468585] hover:bg-[#468585] text-white font-medium px-4 py-2 rounded transition">
                                        kembali
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 border-t border-gray-200 pt-4 flex items-center space-x-4">
                            <img class="h-12 w-12 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff"
                                alt="Profile photo">

                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-gray-500">Member since
                                    {{ auth()->user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Profile Information -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                                <p class="mt-1 text-sm text-gray-500">Update your basic account details</p>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <form method="POST" action="{{ route('info-profile.update') }}">
                                    @csrf


                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="name"
                                                class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" name="name" id="name"
                                                value="{{ old('name', auth()->user()->name) }}"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="email"
                                                class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" name="email" id="email"
                                                value="{{ old('email', auth()->user()->email) }}"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="mt-10 flex justify-end">

                                        <button type="submit"
                                            class="ml-3 bg-[#468585] hover:bg-[#468585] text-white py-2 px-4 rounded-md text-sm font-medium">
                                            Save changes
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>

                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Security</h3>
                                <p class="mt-1 text-sm text-gray-500">Change your password</p>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <form method="POST" action="{{ route('info-profile.update-password') }}">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">

                                        <!-- New Password -->
                                        <div class="sm:col-span-6 relative">
                                            <label for="new-password"
                                                class="block text-sm font-medium text-gray-700">New password</label>
                                            <input type="password" id="new-password" name="new_password"
                                                class="mt-1 block w-full border rounded-md p-2 pr-10 border-gray-300 sm:text-sm" />
                                            <button type="button"
                                                class="absolute inset-y-0 right-2 flex items-center text-gray-500 cursor-pointer hover:text-gray-700 outline-none"
                                                onclick="togglePassword('new-password', this)" tabindex="-1"
                                                aria-label="Toggle new password visibility">
                                                <!-- Eye icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="sm:col-span-6 relative">
                                            <label for="confirm-password"
                                                class="block text-sm font-medium text-gray-700">Confirm password</label>
                                            <input type="password" id="confirm-password"
                                                name="new_password_confirmation"
                                                class="mt-1 block w-full border rounded-md p-2 pr-10 border-gray-300 sm:text-sm" />
                                            <button type="button"
                                                class="absolute inset-y-0 right-2 flex items-center text-gray-500 cursor-pointer hover:text-gray-700 outline-none"
                                                onclick="togglePassword('confirm-password', this)" tabindex="-1"
                                                aria-label="Toggle confirm password visibility">
                                                <!-- Eye icon -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">

                                        <button type="submit"
                                            class="ml-3 bg-[#468585] hover:bg-[#468585] text-white py-2 px-4 rounded-md text-sm font-medium">
                                            Update password
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>



                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>


<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            btn.innerHTML = `
                <!-- Eye Off icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.955 9.955 0 012.933-4.357m2.5-1.143A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.056 10.056 0 01-1.524 2.765M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3l18 18" />
                </svg>
            `;
        } else {
            input.type = "password";
            btn.innerHTML = `
                <!-- Eye icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            `;
        }
    }
</script>
