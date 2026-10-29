@section('title', 'Kelola Pegawai')
<x-app-layout>
    @include('layouts.navbar-owner')

    <div class="bg-[#D1DDD5] min-h-screen pt-28 px-4 sm:px-8 pb-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <h1 class="text-xl font-semibold text-[#2B7A78]">Kelola Pegawai</h1>

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Tambah pegawai --}}
            <form method="POST" action="{{ route('pegawai.store') }}" class="bg-white rounded-xl p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf
                <h2 class="sm:col-span-2 font-semibold text-gray-800">Tambah pegawai</h2>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Username (untuk login)" required
                    class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
                    class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                <select name="role" class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <input type="password" name="password" placeholder="Password (min. 8 karakter)" required autocomplete="new-password"
                    class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                <button type="submit" class="sm:col-span-2 py-2.5 rounded-lg text-white font-semibold bg-[#2B7A78] hover:bg-[#205C5D]">Tambah</button>
            </form>

            {{-- Daftar pegawai --}}
            <div class="space-y-3">
                @forelse ($pegawai as $p)
                    <div class="bg-white rounded-xl p-4" x-data="{ edit: false }">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-gray-800">{{ $p->name }}
                                    <span class="ml-1 text-xs font-semibold uppercase text-white px-2 py-0.5 rounded {{ $p->role === 'kasir' ? 'bg-[#DB5461]' : 'bg-[#468585]' }}">{{ $p->role }}</span>
                                </p>
                                <p class="text-sm text-gray-500">{{ $p->email }}</p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="edit = !edit" class="text-sm px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-100">Ubah</button>
                                <form method="POST" action="{{ route('pegawai.destroy', $p) }}" onsubmit="return confirm('Hapus akun {{ $p->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm px-3 py-1.5 rounded-md text-red-600 border border-red-200 hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <form x-show="edit" x-cloak method="POST" action="{{ route('pegawai.update', $p) }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $p->name }}" required class="px-3 py-2 rounded-lg border border-gray-300">
                            <input type="email" name="email" value="{{ $p->email }}" required class="px-3 py-2 rounded-lg border border-gray-300">
                            <select name="role" class="px-3 py-2 rounded-lg border border-gray-300">
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" @selected($p->role === $role)>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                            <input type="password" name="password" placeholder="Password baru (kosongkan bila tidak diganti)" autocomplete="new-password"
                                class="px-3 py-2 rounded-lg border border-gray-300">
                            <button type="submit" class="sm:col-span-2 py-2 rounded-lg text-white font-semibold bg-[#2B7A78] hover:bg-[#205C5D]">Simpan</button>
                        </form>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-8 text-center text-gray-500">Belum ada pegawai.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
