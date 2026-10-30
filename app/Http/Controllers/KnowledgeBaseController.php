<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use App\Exports\KnowledgeBaseTemplateExport;
use App\Imports\KnowledgeBaseImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $perPage = $request->query('per_page', '10'); // Default 10 data per halaman

        $query = KnowledgeBase::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('source', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('updated_at');

        // Logika penanganan Tampilkan Semua vs Pagination Angka
        if ($perPage === 'all') {
            $total = $query->count();
            // Jika data kosong, gunakan default 1 agar paginate tidak eror
            $knowledgeBases = $query->paginate($total > 0 ? $total : 1)->withQueryString();
        } else {
            // Konversi ke integer (validasi angka misal 10, 25, 50, 100)
            $perPageInt = in_array((int)$perPage, [10, 25, 50, 100]) ? (int)$perPage : 10;
            $knowledgeBases = $query->paginate($perPageInt)->withQueryString();
        }

        return view('knowledge-base.index', compact(
            'knowledgeBases',
            'search',
            'perPage'
        ));
    }

    public function show(string $id): View
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);

        return view('knowledge-base.show', compact('knowledgeBase'));
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new KnowledgeBaseTemplateExport(),
            'template_knowledge_base.xlsx'
        );
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],
        ], [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {
            $file = $request->file('file');

            // Baca header dari baris pertama sebelum mengimpor data.
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(
                $file->getRealPath()
            );

            $worksheet = $spreadsheet->getActiveSheet();

            $headers = $worksheet->rangeToArray(
                'A1:' . $worksheet->getHighestDataColumn() . '1',
                null,
                true,
                false
            )[0] ?? [];

            $headers = array_map(
                fn($header) => strtolower(trim((string) $header)),
                $headers
            );

            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            $expectedHeaders = [
                'title',
                'category',
                'content',
                'source',
            ];

            // Header wajib harus lengkap dan tidak boleh ada kolom tambahan.
            $missingHeaders = array_diff($expectedHeaders, $headers);
            $extraHeaders = array_diff($headers, $expectedHeaders);

            if (
                !empty($missingHeaders) ||
                !empty($extraHeaders) ||
                count($headers) !== count($expectedHeaders) ||
                count(array_unique($headers)) !== count($headers)
            ) {
                $messages = [];

                if (!empty($missingHeaders)) {
                    $messages[] = 'Kolom yang belum ada: '
                        . implode(', ', $missingHeaders) . '.';
                }

                if (!empty($extraHeaders)) {
                    $messages[] = 'Kolom yang tidak dikenali: '
                        . implode(', ', $extraHeaders) . '.';
                }

                if (
                    empty($missingHeaders) &&
                    empty($extraHeaders) &&
                    count(array_unique($headers)) !== count($headers)
                ) {
                    $messages[] = 'Terdapat nama kolom yang duplikat.';
                }

                throw ValidationException::withMessages([
                    'file' => 'Format kolom Excel tidak sesuai template. Silakan periksa kembali file Anda. '
                ]);
            }

            $import = new KnowledgeBaseImport();

            DB::transaction(function () use ($import, $file) {
                Excel::import($import, $file);
            });

            return redirect()
                ->route('knowledge-base.index')
                ->with(
                    'success',
                    $import->imported . ' referensi berhasil ditambahkan.'
                );
        } catch (ValidationException $e) {
            return redirect()
                ->route('knowledge-base.index')
                ->withErrors($e->errors())
                ->with('upload_failed', true);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('knowledge-base.index')
                ->withErrors([
                    'file' => 'Import gagal diproses. Pastikan file tidak rusak '
                        . 'dan formatnya sesuai template, lalu coba kembali.',
                ])
                ->with('upload_failed', true);
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);
        $knowledgeBase->delete();

        return redirect()
            ->route('knowledge-base.index')
            ->with('success', 'Referensi Knowledge Base berhasil dihapus.');
    }
}