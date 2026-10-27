{{--
    Field deskripsi menu + tombol "Buat dengan AI".
    Nama & harga diambil dari input name="name" dan name="price" di form yang sama.
--}}
<div x-data="deskripsiMenuAi()" class="mb-4">
    <div class="flex items-center justify-between">
        <label for="{{ $id ?? 'description' }}" class="block text-sm font-medium text-gray-700">
            Deskripsi <span class="text-gray-400 font-normal">(opsional, tampil di kasir)</span>
        </label>
        @if (config('services.vikey.api_key'))
            <button type="button" @click="buat($el)" :disabled="loading"
                class="text-xs font-semibold text-[#2B7A78] hover:underline disabled:opacity-50">
                <span x-show="!loading">✨ Buat dengan AI</span>
                <span x-show="loading" x-cloak>Membuat...</span>
            </button>
        @endif
    </div>
    <textarea name="description" id="{{ $id ?? 'description' }}" rows="2" maxlength="500" x-ref="field"
        class="w-full p-2 mt-1 border border-gray-300 rounded-md text-sm"
        placeholder="cth. Ayam bakar bumbu kecap dengan nasi hangat dan sambal">{{ $value ?? '' }}</textarea>
    <p x-show="error" x-cloak class="mt-1 text-xs text-red-600" x-text="error"></p>
</div>

@once
    <script>
        function deskripsiMenuAi() {
            return {
                loading: false,
                error: '',
                async buat(button) {
                    const form = button.closest('form');
                    const name = form.querySelector('[name=name]')?.value.trim();
                    const price = parseInt(form.querySelector('[name=price]')?.value) || null;
                    if (!name) {
                        this.error = 'Isi nama menu terlebih dahulu.';
                        return;
                    }
                    this.loading = true;
                    this.error = '';
                    try {
                        const res = await fetch(@js(route('ai.deskripsi-menu')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            },
                            body: JSON.stringify({ name, price }),
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data.pesan || data.message || 'Gagal membuat deskripsi.');
                        this.$refs.field.value = data.deskripsi;
                    } catch (e) {
                        this.error = e.message;
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
@endonce
