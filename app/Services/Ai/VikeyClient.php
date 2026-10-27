<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client untuk Vikey Plan (API kompatibel OpenAI: POST /chat/completions).
 */
class VikeyClient
{
    public function configured(): bool
    {
        return filled(config('services.vikey.api_key'));
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     *
     * @throws AiException
     */
    public function chat(array $messages, float $temperature = 0.4, int $maxTokens = 3000): string
    {
        if (! $this->configured()) {
            throw new AiException('Fitur AI belum diaktifkan. Isi VIKEY_API_KEY di file .env.');
        }

        $content = $this->kirim($messages, $temperature, $maxTokens);

        // vikey/plan adalah model reasoning: proses berpikirnya ikut memakai jatah max_tokens.
        // Bila jatah habis sebelum menjawab, coba sekali lagi dengan jatah dua kali lipat.
        if ($content === null) {
            $content = $this->kirim($messages, $temperature, $maxTokens * 2);
        }

        if ($content === null) {
            throw new AiException('AI tidak sempat menyelesaikan jawaban. Coba pertanyaan yang lebih singkat.');
        }

        return $content;
    }

    /**
     * @return string|null jawaban, atau null bila model kehabisan token sebelum menjawab
     */
    private function kirim(array $messages, float $temperature, int $maxTokens): ?string
    {
        try {
            $response = Http::baseUrl(rtrim(config('services.vikey.base_url'), '/'))
                ->withToken(config('services.vikey.api_key'))
                ->acceptJson()
                ->timeout(config('services.vikey.timeout'))
                ->retry(2, 500, fn ($e) => $e instanceof ConnectionException, throw: false)
                ->post('/chat/completions', [
                    'model' => config('services.vikey.model'),
                    'messages' => $messages,
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                ]);
        } catch (ConnectionException $e) {
            Log::warning('AI tidak bisa dihubungi: '.$e->getMessage());

            throw new AiException('Layanan AI tidak bisa dihubungi. Coba lagi sebentar lagi.');
        }

        if ($response->failed()) {
            Log::warning('AI error', ['status' => $response->status(), 'body' => mb_substr($response->body(), 0, 500)]);

            throw new AiException(match (true) {
                in_array($response->status(), [401, 403]) => 'API key AI tidak valid atau langganan tidak aktif.',
                $response->status() === 429 => 'Batas pemakaian AI tercapai. Coba lagi beberapa saat lagi.',
                default => 'Layanan AI sedang bermasalah. Coba lagi sebentar lagi.',
            });
        }

        // Sebagian model menyertakan proses berpikir di tag <think>; buang dari jawaban
        $content = trim(preg_replace('/<think>.*?<\/think>/s', '', (string) $response->json('choices.0.message.content')));

        // Kosong, atau pesan pengganti dari Vikey saat jatah token habis untuk berpikir
        if ($content === '' || str_starts_with($content, '[No answer')) {
            Log::info('AI kehabisan token sebelum menjawab', ['max_tokens' => $maxTokens, 'usage' => $response->json('usage')]);

            return null;
        }

        return $content;
    }
}
