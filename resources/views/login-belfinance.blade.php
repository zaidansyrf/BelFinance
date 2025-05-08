<x-app-layout>
    <title>BelFinance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="grid justify-items-center content-center h-svh w-full bg-[#D1DDD5]">
        
        <!-- Title with Icon -->
        <div class="flex items-center mb-20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 mr-2" style="color: #2B7A78;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h1 class="text-4xl font-bold text-primary text-center" style="color: #2B7A78;">BelFinance</h1>
        </div>

        <div class="card bg-[#F7F7F7] text-primary-content w-96">
            <!-- Content here -->
            <div class="card-body">
                <input type="text" placeholder="Username" class="mb-2 input input-bordered text-[#2B7A78] w-full max-w-xs bg-white mt-12 " />
                <input type="text" placeholder="Password" class="input input-bordered text-[#2B7A78] w-full max-w-xs bg-white" />
                <div class="card-actions justify-center mt-6">
                    <button class="btn btn-active" style="background-color: #2B7A78; color: white;">LOGIN</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
