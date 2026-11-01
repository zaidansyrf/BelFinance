{{-- Rencana tindakan dari AI, dibuat dari temuan aturan + data per menu. Dimuat setelah halaman terbuka. --}}
@if (config('services.vikey.api_key'))
    <div x-data="saranAi()" x-init="muat()" class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wide text-white bg-[#2B7A78] px-2 py-0.5 rounded">AI</span>
                <h2 class="font-semibold text-gray-800">Rencana Tindakan</h2>
                <span x-show="dibuat" x-cloak class="text-xs text-gray-400" x-text="`dibuat ${dibuat}`"></span>
            </div>
            <button type="button" @click="muat(true)" :disabled="loading" class="text-sm text-[#2B7A78] hover:underline disabled:opacity-50">Perbarui</button>
        </div>
        <div x-show="loading" class="text-sm text-gray-500"><span class="loading loading-dots loading-sm"></span> AI sedang membaca data penjualan...</div>
        <p x-show="error" x-cloak class="text-sm text-red-600" x-text="error"></p>
        <div x-show="!loading && html" x-cloak class="ai-prose text-sm text-gray-800" x-html="html"></div>
    </div>

    <script>
        function saranAi() {
            return {
                loading: false,
                html: '',
                dibuat: '',
                error: '',
                async muat(segarkan = false) {
                    this.loading = true;
                    this.error = '';
                    try {
                        const res = await fetch(@js(route('ai.saran-usaha')), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            },
                            body: JSON.stringify({ segarkan }),
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data.pesan || 'Saran AI gagal dimuat.');
                        this.html = data.html;
                        this.dibuat = data.dibuat;
                    } catch (e) {
                        this.error = e.message;
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>
@endif
