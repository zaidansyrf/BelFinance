{{-- Panel chat "Tanya AI". Riwayat percakapan disimpan di browser (tidak di database). --}}
<div x-data="asistenAi()" class="bg-white rounded-xl shadow-sm flex flex-col h-[calc(100vh-12rem)] min-h-[480px]">
    <div class="px-5 py-4 border-b flex items-center justify-between">
        <div>
            <h2 class="font-semibold text-gray-800">Tanya AI</h2>
            <p class="text-xs text-gray-500">Jawaban dibuat dari ringkasan data keuangan BelFinance. Tetap cek ulang angka penting di Laporan.</p>
        </div>
        <button type="button" x-show="messages.length" @click="reset()" class="text-sm text-red-500 hover:text-red-700">Percakapan baru</button>
    </div>

    @unless ($aktif)
        <div class="m-5 rounded-lg bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800">
            Fitur AI belum aktif. Isi <code class="font-mono">VIKEY_API_KEY</code> di file <code class="font-mono">.env</code>, lalu muat ulang halaman ini.
        </div>
    @endunless

    <div x-ref="list" class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
        <template x-if="!messages.length">
            <div class="text-center text-gray-500 py-6">
                <p class="mb-4">Coba tanyakan:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <template x-for="contoh in contohPertanyaan" :key="contoh">
                        <button type="button" @click="kirim(contoh)" :disabled="loading || !aktif"
                            class="text-sm px-3 py-2 rounded-full border border-[#468585] text-[#2B7A78] hover:bg-[#468585] hover:text-white disabled:opacity-50"
                            x-text="contoh"></button>
                    </template>
                </div>
            </div>
        </template>

        <template x-for="(m, i) in messages" :key="i">
            <div class="flex" :class="m.role === 'user' ? 'justify-end' : 'justify-start'">
                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm"
                    :class="m.role === 'user' ? 'bg-[#2B7A78] text-white rounded-br-sm' : (m.error ? 'bg-red-50 text-red-700 rounded-bl-sm' : 'bg-gray-100 text-gray-800 rounded-bl-sm')">
                    <template x-if="m.role === 'user' || m.error"><p class="whitespace-pre-line" x-text="m.content"></p></template>
                    <template x-if="m.role === 'assistant' && !m.error"><div class="ai-prose" x-html="m.html"></div></template>
                </div>
            </div>
        </template>

        <div x-show="loading" x-cloak class="flex justify-start">
            <div class="bg-gray-100 rounded-2xl rounded-bl-sm px-4 py-3 text-sm text-gray-500">
                <span class="loading loading-dots loading-sm"></span> Sedang menganalisis data...
            </div>
        </div>
    </div>

    <form @submit.prevent="kirim()" class="border-t p-3 flex gap-2">
        <textarea x-model="input" x-ref="input" rows="1" maxlength="1000" :disabled="!aktif"
            @keydown.enter.prevent="if (!$event.shiftKey) kirim(); else input += '\n'"
            placeholder="Tulis pertanyaan, mis. 'Kenapa laba bulan ini turun?'"
            class="flex-1 resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-[#468585] focus:border-[#468585] disabled:bg-gray-100"></textarea>
        <button type="submit" :disabled="loading || !input.trim() || !aktif"
            class="px-5 rounded-lg bg-[#2B7A78] text-white font-semibold hover:bg-[#205C5D] disabled:bg-gray-300">Kirim</button>
    </form>
</div>

<script>
    function asistenAi() {
        const KEY = 'belfinance-ai-chat-{{ auth()->id() }}';
        let tersimpan = [];
        try { tersimpan = JSON.parse(sessionStorage.getItem(KEY) || '[]'); } catch (e) {}

        return {
            aktif: @js($aktif),
            messages: tersimpan,
            input: '',
            loading: false,
            contohPertanyaan: [
                'Bagaimana penjualan minggu ini dibanding minggu lalu?',
                'Menu apa yang paling laku bulan ini?',
                'Pengeluaran terbesar bulan ini untuk apa?',
                'Hari apa yang paling sepi?',
            ],

            simpan() {
                try { sessionStorage.setItem(KEY, JSON.stringify(this.messages.slice(-30))); } catch (e) {}
            },
            gulir() {
                this.$nextTick(() => this.$refs.list.scrollTop = this.$refs.list.scrollHeight);
            },
            reset() {
                this.messages = [];
                this.simpan();
            },
            async kirim(teks = null) {
                const pesan = (teks ?? this.input).trim();
                if (!pesan || this.loading) return;

                // Riwayat yang dikirim: pesan sebelumnya yang bukan error
                const riwayat = this.messages.filter((m) => !m.error).slice(-10).map(({ role, content }) => ({ role, content }));
                this.messages.push({ role: 'user', content: pesan });
                this.input = '';
                this.loading = true;
                this.gulir();

                try {
                    const res = await fetch(@js(route('ai.tanya')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ pesan, riwayat }),
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) {
                        const pesanError = data.pesan || data.message || (res.status === 429 ? 'Terlalu banyak pertanyaan. Tunggu sebentar.' : 'Terjadi kesalahan.');
                        this.messages.push({ role: 'assistant', content: pesanError, error: true });
                    } else {
                        this.messages.push({ role: 'assistant', content: data.jawaban, html: data.html });
                    }
                } catch (e) {
                    this.messages.push({ role: 'assistant', content: 'Koneksi terputus. Coba lagi.', error: true });
                } finally {
                    this.loading = false;
                    this.simpan();
                    this.gulir();
                    this.$nextTick(() => this.$refs.input.focus());
                }
            },
        };
    }
</script>
