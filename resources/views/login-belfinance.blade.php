<x-app-layout>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="grid justify-items-center content-center h-svh w-full bg-[#F7F7F7]">

        <div class="card bg-white text-primary-content w-96">
            <div class="card-body">
                <input type="text" placeholder="Username" class="input input-bordered w-full max-w-xs bg-white mt-12" />
                <input type="text" placeholder="Password" class="input input-bordered w-full max-w-xs bg-white" />
                <div class="card-actions justify-center mt-6">
                    <button class="btn btn-accent text-white">Masuk</button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>