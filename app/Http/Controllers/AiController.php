<?php

namespace App\Http\Controllers;

use App\Services\Ai\AiException;
use App\Services\Ai\AsistenKeuangan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AiController extends Controller
{
    // Batas pertanyaan per hari untuk role keuangan; owner tanpa batas
    public const BATAS_HARIAN_KEUANGAN = 20;

    public function __construct(private AsistenKeuangan $asisten) {}

    /**
     * Halaman chat "Tanya AI" untuk owner dan keuangan.
     */
    public function asisten()
    {
        return view('ai.asisten', ['aktif' => $this->asisten->aktif()]);
    }

    public function tanya(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pesan' => 'required|string|max:1000',
            'riwayat' => 'nullable|array|max:20',
            'riwayat.*.role' => 'required|in:user,assistant',
            'riwayat.*.content' => 'required|string|max:4000',
        ]);

        if ($pesanBatas = $this->batasHarian($request)) {
            return response()->json(['pesan' => $pesanBatas], 429);
        }

        return $this->jalankan(function () use ($data) {
            $jawaban = $this->asisten->tanya($data['pesan'], $data['riwayat'] ?? []);

            return ['jawaban' => $jawaban, 'html' => AsistenKeuangan::keHtml($jawaban)];
        });
    }

    public function insight(Request $request): JsonResponse
    {
        return $this->jalankan(function () use ($request) {
            $insight = $this->asisten->insight($request->boolean('segarkan'));

            return ['html' => AsistenKeuangan::keHtml($insight['isi']), 'dibuat' => $insight['dibuat']];
        });
    }

    public function deskripsiMenu(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|integer|min:0',
        ]);

        return $this->jalankan(fn () => [
            'deskripsi' => $this->asisten->deskripsiMenu($data['name'], $data['price'] ?? null),
        ]);
    }

    /**
     * Keuangan dibatasi pertanyaan per hari; owner bebas. Mengembalikan pesan bila batas habis.
     */
    private function batasHarian(Request $request): ?string
    {
        $user = $request->user();
        if ($user->isOwner()) {
            return null;
        }

        $key = 'ai-tanya-harian:'.$user->id.':'.now()->toDateString();
        if (RateLimiter::tooManyAttempts($key, self::BATAS_HARIAN_KEUANGAN)) {
            return 'Batas '.self::BATAS_HARIAN_KEUANGAN.' pertanyaan per hari sudah habis. Coba lagi besok atau hubungi owner.';
        }

        RateLimiter::hit($key, 86400);

        return null;
    }

    private function jalankan(callable $aksi): JsonResponse
    {
        try {
            return response()->json($aksi());
        } catch (AiException $e) {
            return response()->json(['pesan' => $e->getMessage()], 503);
        }
    }
}
