<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Fitur AI BelFinance: tanya-jawab keuangan, insight dashboard, dan deskripsi menu.
 */
class AsistenKeuangan
{
    private const ATURAN_DASAR = <<<'TXT'
        Kamu adalah asisten keuangan untuk pemilik dan staf keuangan Belindo Kitchen.
        Aturan:
        - Jawab dalam bahasa Indonesia yang singkat, jelas, dan sopan.
        - Gunakan HANYA angka dari DATA di bawah. Jangan mengarang angka. Jika data yang ditanyakan tidak ada,
          katakan terus terang bahwa data itu tidak tersedia di ringkasan.
        - Bila perlu merujuk ke aplikasi, sebut halaman yang tepat: "Laporan Keuangan" untuk detail transaksi,
          "Menu" untuk daftar menu dan harga, "Pengeluaran" untuk rincian biaya.
        - Tulis uang dalam format Rupiah, mis. Rp 1.250.000.
        - Boleh memakai Markdown sederhana (paragraf, daftar, **tebal**, tabel kecil). Jangan memakai HTML.
        - Jangan memberikan nasihat investasi atau pajak yang bersifat pasti; cukup saran operasional usaha.
        TXT;

    public function __construct(
        private VikeyClient $client,
        private KonteksKeuangan $konteks,
    ) {}

    public function aktif(): bool
    {
        return $this->client->configured();
    }

    /**
     * Jawab pertanyaan pengguna berdasarkan ringkasan data keuangan.
     *
     * @param  array<int, array{role: string, content: string}>  $riwayat  percakapan sebelumnya (user/assistant)
     */
    public function tanya(string $pertanyaan, array $riwayat = []): string
    {
        $messages = [['role' => 'system', 'content' => $this->sistemDenganData()]];

        foreach (array_slice($riwayat, -10) as $pesan) {
            if (in_array($pesan['role'] ?? null, ['user', 'assistant'], true) && filled($pesan['content'] ?? null)) {
                $messages[] = ['role' => $pesan['role'], 'content' => Str::limit($pesan['content'], 2000)];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $pertanyaan];

        return $this->client->chat($messages, temperature: 0.3);
    }

    /**
     * Insight otomatis untuk dashboard. Disimpan di cache 30 menit agar tidak dibuat ulang setiap kali halaman dibuka.
     *
     * @return array{isi: string, dibuat: string}
     */
    public function insight(bool $segarkan = false): array
    {
        $key = 'ai-insight:'.now()->toDateString();
        if ($segarkan) {
            Cache::forget($key);
        }

        return Cache::remember($key, now()->addMinutes(30), function () {
            $isi = $this->client->chat([
                ['role' => 'system', 'content' => $this->sistemDenganData()],
                ['role' => 'user', 'content' => <<<'TXT'
                    Buat insight singkat untuk dashboard hari ini dengan format:
                    **Ringkasan** – 2-3 kalimat kondisi penjualan, pengeluaran, dan laba bulan ini dibanding bulan lalu.
                    **Yang perlu diperhatikan** – maksimal 3 poin (mis. pengeluaran yang naik, hari sepi, menu yang tidak laku).
                    **Saran** – maksimal 3 poin tindakan konkret untuk minggu ini.
                    Total maksimal 180 kata.
                    TXT],
            ], temperature: 0.4, maxTokens: 3000);

            return ['isi' => $isi, 'dibuat' => now()->format('H:i')];
        });
    }

    /**
     * Deskripsi singkat yang menarik untuk sebuah menu.
     */
    public function deskripsiMenu(string $nama, ?int $harga = null): string
    {
        $info = $harga ? "Nama menu: {$nama}\nHarga: Rp ".number_format($harga, 0, ',', '.') : "Nama menu: {$nama}";

        $hasil = $this->client->chat([
            ['role' => 'system', 'content' => 'Kamu penulis menu untuk rumah makan Indonesia "Belindo Kitchen". '
                .'Tulis SATU deskripsi menu dalam bahasa Indonesia, 1-2 kalimat, maksimal 25 kata, menggugah selera, '
                .'tanpa emoji, tanpa tanda kutip, tanpa menyebut harga, dan tanpa klaim yang tidak bisa dipastikan (mis. "terenak se-Indonesia"). '
                .'Singkatan di awal nama seperti "KO" atau "ALC" adalah kode kategori internal; abaikan.'],
            ['role' => 'user', 'content' => $info],
        ], temperature: 0.8, maxTokens: 1000);

        return Str::limit(trim($hasil, " \t\n\r\0\x0B\"'“”"), 300, '');
    }

    /**
     * Ubah Markdown jawaban AI menjadi HTML yang aman (HTML mentah dibuang).
     */
    public static function keHtml(string $markdown): string
    {
        return Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    private function sistemDenganData(): string
    {
        $data = json_encode($this->konteks->ringkasan(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return self::ATURAN_DASAR."\n\nDATA (ringkasan otomatis dari database BelFinance):\n".$data;
    }
}
