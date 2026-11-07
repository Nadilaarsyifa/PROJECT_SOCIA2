<?php

namespace App\Services;

use App\Models\KnowledgeBase;
use Illuminate\Support\Collection;

class KnowledgeBaseRetriever
{
    public function retrieve(string $alert, ?string $detection = null): Collection
    {
        $stopWords = [
            'yang', 'dan', 'atau', 'dari', 'dengan', 'untuk',
            'pada', 'dalam', 'terdeteksi', 'terdapat', 'adalah',
            'this', 'that', 'with', 'from', 'have', 'has',
            'the', 'and', 'for', 'was', 'were', 'alert',
            'event', 'detected', 'activity', 'potential',
            'attempt', 'general', 'authentication',
            'incident', 'security', 'system', 'proses',
            'melakukan', 'terhadap', 'secara', 'melalui',
        ];

        $detectionText = $this->normalize($detection ?? '');
        $detectionKeywords = $this->extractKeywords($detection ?? '', $stopWords);
        $alertKeywords = $this->extractKeywords($alert, $stopWords);

        if ($detectionKeywords->isEmpty() && $alertKeywords->isEmpty()) {
            return collect();
        }

        $allKeywords = $detectionKeywords->merge($alertKeywords)->unique()->values();

        $candidates = KnowledgeBase::query()
            ->where(function ($query) use ($allKeywords) {
                foreach ($allKeywords as $keyword) {
                    $query->orWhere('title', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhere('content', 'like', "%{$keyword}%");
                }
            })
            ->get(['id_knowledge', 'title', 'category', 'content', 'source']);

        return $candidates
            ->map(function ($reference) use ($detectionText, $detectionKeywords, $alertKeywords) {
                $title = $this->normalize($reference->title ?? '');
                $category = $this->normalize($reference->category ?? '');
                $content = $this->normalize($reference->content ?? '');

                $score = 0;

                $incidentType = $this->normalize(
                    $this->extractIncidentType($reference->content ?? '')
                );

                if (
                    $detectionText !== '' &&
                    $incidentType !== '' &&
                    (
                        $incidentType === $detectionText ||
                        str_contains($incidentType, $detectionText) ||
                        str_contains($detectionText, $incidentType)
                    )
                ) {
                    $score += 30;
                } else {
                    foreach ($detectionKeywords as $keyword) {
                        if ($incidentType !== '' && str_contains($incidentType, $keyword)) {
                            $score += 8;
                        }
                    }
                }

                foreach ($detectionKeywords as $keyword) {
                    if (str_contains($title, $keyword)) {
                        $score += 10;
                    }

                    if (str_contains($category, $keyword)) {
                        $score += 3;
                    }
                }

                foreach ($alertKeywords as $keyword) {
                    if (str_contains($title, $keyword)) {
                        $score += 3;
                    }

                    if (str_contains($category, $keyword)) {
                        $score += 1;
                    }

                    if (str_contains($content, $keyword)) {
                        $score += 0.25;
                    }
                }

                $reference->relevance_score = $score;

                return $reference;
            })
            ->filter(fn ($reference) => $reference->relevance_score >= 8)
            ->unique(function ($reference) {
                return md5(
                    $this->normalize($reference->title) . '|' .
                    $this->normalize($reference->content)
                );
            })
            ->sortByDesc('relevance_score')
            ->take(3)
            ->values();
    }

    private function extractIncidentType(string $content): string
    {
        if (preg_match(
            '/Jenis\s+Insiden\s+Sektor\s*:\s*([^\r\n]+)/iu',
            $content,
            $matches
        )) {
            return trim($matches[1]);
        }

        return '';
    }

    private function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $text);

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    private function extractKeywords(string $text, array $stopWords): Collection
    {
        $words = preg_split(
            '/[^\p{L}\p{N}]+/u',
            mb_strtolower($text),
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        return collect($words)
            ->filter(fn ($word) => mb_strlen($word) >= 4)
            ->reject(fn ($word) => in_array($word, $stopWords, true))
            ->unique()
            ->values();
    }
}
