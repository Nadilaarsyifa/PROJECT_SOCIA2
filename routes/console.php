<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\OllamaService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Artisan::command('ollama:test', function (OllamaService $ollama) {
    $this->info('Menghubungi Ollama...');

    try {
        $result = $ollama->generate(
            'Jelaskan secara singkat apa itu port scanning dalam keamanan jaringan. Jawab dalam bahasa Indonesia, maksimal 3 kalimat.'
        );

        $this->newLine();
        $this->info('Respons dari Gemma 3:4B:');
        $this->line($result);
    } catch (\Throwable $e) {
        $this->error($e->getMessage());
    }
});
