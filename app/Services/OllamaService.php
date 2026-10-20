<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use RuntimeException;

class OllamaService
{
    private string $baseUrl = 'http://127.0.0.1:11434';

    private string $model = 'gemma3:4b';

    /**
     * Mengirim prompt ke Ollama dan mengambil hasilnya.
     */
    public function generate(string $prompt): string
    {
        try {
            $startTime = microtime(true);
            $response = Http::connectTimeout(10)
                ->timeout(240)
                ->post($this->baseUrl . '/api/generate', [
                    'model' => $this->model,
                    'prompt' => $prompt,
                    'stream' => false,
                    'keep_alive' => '30m',
                    'options' => [
                        'num_predict' => 512,
                    ],
                ]);
            logger()->info('Waktu respons Ollama', [
                'seconds' => round(microtime(true) - $startTime, 2),
            ]);
        } catch (ConnectionException $e) {
            throw new RuntimeException(
                'Ollama tidak dapat dihubungi. Pastikan Ollama sedang berjalan.'
            );
        }

        if ($response->failed()) {
            throw new RuntimeException(
                'Ollama gagal memproses permintaan: ' . $response->status()
            );
        }

        $result = $response->json('response');

        if (!is_string($result) || trim($result) === '') {
            throw new RuntimeException(
                'Ollama tidak memberikan hasil analisis.'
            );
        }

        return trim($result);
    }
}
