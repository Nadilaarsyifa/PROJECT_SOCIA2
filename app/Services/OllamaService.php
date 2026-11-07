<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use RuntimeException;

class OllamaService
{
    private string $baseUrl = 'http://127.0.0.1:11434';

    private string $model = 'gemma3:4b';

    public function generate(string $prompt): string
    {
        try {
            $startTime = microtime(true);

            $schema = [
                'type' => 'object',
                'properties' => [
                    'summary' => [
                        'type' => 'string',
                    ],
                    'attention_points' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                        'minItems' => 1,
                        'maxItems' => 3,
                    ],
                    'handling_steps' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                        'minItems' => 1,
                    ],
                ],
                'required' => [
                    'summary',
                    'attention_points',
                    'handling_steps',
                ],
                'additionalProperties' => false,
            ];

            $response = Http::connectTimeout(10)
                ->timeout(240)
                ->post($this->baseUrl . '/api/generate', [
                    'model' => $this->model,
                    'prompt' => $prompt,
                    'stream' => false,
                    'format' => $schema,
                    'keep_alive' => '30m',
                    'options' => [
                        'temperature' => 0,
                        'num_predict' => 1200,
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
            logger()->error('Ollama API gagal', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 1000),
            ]);

            throw new RuntimeException(
                'Ollama gagal memproses permintaan: ' . $response->status()
            );
        }

        $ollamaData = $response->json();

        logger()->info('Metadata respons Ollama', [
            'done' => $ollamaData['done'] ?? null,
            'done_reason' => $ollamaData['done_reason'] ?? null,
            'eval_count' => $ollamaData['eval_count'] ?? null,
            'prompt_eval_count' => $ollamaData['prompt_eval_count'] ?? null,
            'total_duration' => $ollamaData['total_duration'] ?? null,
            'eval_duration' => $ollamaData['eval_duration'] ?? null,
            'response_length' => isset($ollamaData['response'])
                && is_string($ollamaData['response'])
                    ? strlen($ollamaData['response'])
                    : null,
        ]);

        $result = $ollamaData['response'] ?? null;

        if (!is_string($result) || trim($result) === '') {
            throw new RuntimeException(
                'Ollama tidak memberikan hasil analisis.'
            );
        }

        return trim($result);
    }
}