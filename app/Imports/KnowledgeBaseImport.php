<?php

namespace App\Imports;

use App\Models\KnowledgeBase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class KnowledgeBaseImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $imported = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows): void
    {
        $validRows = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            // Header berada di baris 1, data pertama di baris 2.
            $rowNumber = $index + 2;

            $data = [
                'title' => trim((string) ($row['title'] ?? '')),
                'category' => trim((string) ($row['category'] ?? '')),
                'content' => trim((string) ($row['content'] ?? '')),
                'source' => trim((string) ($row['source'] ?? '')),
            ];

            $validator = Validator::make($data, [
                'title' => ['required', 'string', 'max:255'],
                'category' => ['nullable', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'source' => ['nullable', 'string', 'max:255'],
            ], [
                'title.required' => 'Kolom title wajib diisi.',
                'title.max' => 'Kolom title maksimal 255 karakter.',
                'content.required' => 'Kolom content wajib diisi.',
                'category.max' => 'Kolom category maksimal 255 karakter.',
                'source.max' => 'Kolom source maksimal 255 karakter.',
            ]);

            if ($validator->fails()) {
                $errors[] = "Baris {$rowNumber}: "
                    . $validator->errors()->first();

                continue;
            }

            $data['category'] = $data['category'] !== ''
                ? $data['category']
                : null;

            $data['source'] = $data['source'] !== ''
                ? $data['source']
                : null;

            $validRows[] = $data;
        }

        // Tolak seluruh file jika ada baris yang tidak valid.
        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'file' => implode(' ', $errors),
            ]);
        }

        if (empty($validRows)) {
            throw ValidationException::withMessages([
                'file' => 'File Excel belum memiliki data referensi yang valid.',
            ]);
        }

        // Penyimpanan dilakukan setelah semua baris lolos validasi.
        foreach ($validRows as $data) {
            KnowledgeBase::create($data);
            $this->imported++;
        }
    }
}
