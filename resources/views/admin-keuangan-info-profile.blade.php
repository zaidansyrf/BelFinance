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
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Active
                            </span>
                        </div>
                        <div class="mt-4 border-t border-gray-200 pt-4 flex items-center space-x-4">
                            <img class="h-12 w-12 rounded-full"
                                src="https://ui-avatars.com/api/?name=User+Name&background=random" alt="Profile photo">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">User Name</h3>
                                <p class="text-sm text-gray-500">Member since Jan 2024</p>
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
                                <form>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                                        <div class="sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Full
                                                name</label>
                                            <input type="text" name="name" id="name"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-3">
                                            <label for="username"
                                                class="block text-sm font-medium text-gray-700">Username</label>
                                            <input type="text" name="username" id="username"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-4">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email
                                                address</label>
                                            <input type="email" name="email" id="email"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>


                                    </div>
                                    <div class="mt-10 flex justify-end">
                                        <button type="reset"
                                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                        <button type="submit"
                                            class="ml-3 bg-[#468585] hover:bg-[#468585] text-white py-2 px-4 rounded-md text-sm font-medium">Save
                                            changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Security / Change Password -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Security</h3>
                                <p class="mt-1 text-sm text-gray-500">Change your password</p>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <form>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
                                        <div class="sm:col-span-6">
                                            <label for="current-password"
                                                class="block text-sm font-medium text-gray-700">Current password</label>
                                            <input type="password" id="current-password" name="current-password"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 sm:text-sm">
                                        </div>

                                        <div class="sm:col-span-6">
                                            <label for="new-password"
                                                class="block text-sm font-medium text-gray-700">New password</label>
                                            <input type="password" id="new-password" name="new-password"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 sm:text-sm">
                                            <p class="mt-2 text-sm text-gray-500">Must be at least 8 characters</p>
                                        </div>

                                        <div class="sm:col-span-6">
                                            <label for="confirm-password"
                                                class="block text-sm font-medium text-gray-700">Confirm password</label>
                                            <input type="password" id="confirm-password" name="confirm-password"
                                                class="mt-1 block w-full border rounded-md p-2 border-gray-300 sm:text-sm">
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <button type="reset"
                                            class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                                        <button type="submit"
                                            class="ml-3 bg-[#468585] hover:bg-[#468585] text-white py-2 px-4 rounded-md text-sm font-medium">Update
                                            password</button>
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
