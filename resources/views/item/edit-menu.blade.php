@section('title', 'Edit Menu')
<x-app-layout>
    <div class="h-screen w-full bg-[#F5F7F6] flex overflow-hidden">
        <!-- Sidebar Toggle -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <div class="drawer-content flex flex-col h-screen">
                <!-- Navbar -->
                @include('layouts.navbar')

                <!-- Main Content -->
                <main class="flex-1 overflow-auto bg-[#D1DDD5]">
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <!-- Page Header -->
                        <div class="flex justify-between items-center mb-8">
                            <h1 class="text-2xl font-semibold text-[#2B7A78]">Edit Menu</h1>
                        </div>
                        <div class="mb-4">
                            <button type="button"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]"
                                onclick="window.location.href='{{ url('/keuangan/menu') }}'">
                                &larr; Kembali
                            </button>
                        </div>
                        <!-- Edit Form Card -->
                        <div class="bg-white rounded-xl shadow-sm border border-[#E0E6E5] p-6">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Edit Menu</h1>
                            <form action="{{ route('menu.update', $item->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="space-y-6">
                                    <!-- Name Field -->
                                    <div>
                                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                            Item Code
                                        </label>
                                        <input type="text" id="code" name="code" value="{{ $item->code }}"
                                            required
                                            class="w-full px-4 py-2.5 border border-[#D1DDD5] rounded-lg focus:ring-2 focus:ring-[#468585] focus:border-[#468585] placeholder-gray-400">
                                    </div>
                                    <div>

                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Item Name
                                        </label>
                                        <input type="text" id="name" name="name" value="{{ $item->name }}"
                                            required
                                            class="w-full px-4 py-2.5 border border-[#D1DDD5] rounded-lg focus:ring-2 focus:ring-[#468585] focus:border-[#468585] placeholder-gray-400">
                                    </div>

                                    <!-- Quantity Field -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Quantity
                                        </label>
                                        <input type="number" id="quantity" name="quantity"
                                            value="{{ $item->quantity }}" required
                                            class="w-full px-4 py-2.5 border border-[#D1DDD5] rounded-lg focus:ring-2 focus:ring-[#468585] focus:border-[#468585] placeholder-gray-400">
                                    </div>

                                    <!-- Price Field -->
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                            Price
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                            <input type="number" id="price" name="price"
                                                value="{{ $item->price }}" required
                                                class="w-full pl-10 pr-4 py-2.5 border border-[#D1DDD5] rounded-lg focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                                        </div>
                                    </div>

                                    <!-- Image Field -->
                                    <div>
                                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                            Gambar <span class="text-gray-400 font-normal">(opsional, JPG/PNG/WEBP, maks. 2 MB)</span>
                                        </label>
                                        <div class="flex items-center gap-4">
                                            <x-menu-image :item="$item" size="h-24 w-24" text="text-2xl" id="imagePreview" />
                                            <div class="flex-1 space-y-2">
                                                <input type="file" id="image" name="image"
                                                    accept="image/png,image/jpeg,image/webp"
                                                    class="file-input file-input-bordered w-full">
                                                @if ($item->image)
                                                    <label class="flex items-center gap-2 text-sm text-gray-600">
                                                        <input type="checkbox" name="remove_image" value="1" class="checkbox checkbox-sm">
                                                        Hapus gambar (kembali ke inisial)
                                                    </label>
                                                @endif
                                            </div>
                                        </div>
                                        @error('image')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="flex justify-end space-x-4 pt-4">
                                        <a href="{{ route('menu.search') }}"
                                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                            Cancel
                                        </a>
                                        <button type="submit"
                                            class="px-6 py-2.5 bg-[#468585] text-white rounded-lg hover:bg-[#3a7171] transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#468585]">
                                            Update Item
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>

            <!-- Sidebar Content -->
            @include('layouts.sidebar')
        </div>
    </div>

    <script>
        // Pratinjau gambar baru sebelum disimpan
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const preview = document.getElementById('imagePreview');
            const img = document.createElement('img');
            img.id = 'imagePreview';
            img.className = 'h-24 w-24 object-cover rounded-lg bg-gray-100';
            img.src = URL.createObjectURL(file);
            preview.replaceWith(img);
        });
    </script>
</x-app-layout>
