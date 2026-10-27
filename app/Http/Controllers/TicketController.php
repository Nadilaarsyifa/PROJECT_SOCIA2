<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\AIAnalysis;
use App\Models\ActionChecklist;
use App\Services\OllamaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Services\TicketParser;
use Throwable;
use App\Exports\AllTicketsExport;
use Maatwebsite\Excel\Facades\Excel;

class TicketController extends Controller
{
    public function reset(Request $request): RedirectResponse
    {
        $request->session()->forget('active_analysis_ticket_no');

        return redirect()
            ->route('tickets.create')
            ->with('success');
    }

    public function create(Request $request): View
    {
        $ticketNo = $request->query('ticket')
            ?? $request->session()->get('active_analysis_ticket_no');

        $analysis = null;
        $ticket = null;

        if ($ticketNo) {
            $analysis = AIAnalysis::with('actionChecklists')
                ->where('no_ticket', $ticketNo)
                ->first();

            if ($analysis) {
                $analysis->setRelation(
                    'actionChecklists',
                    $analysis->actionChecklists
                        ->sortBy('action_order')
                        ->values()
                );

                $ticket = Ticket::where('no_ticket', $ticketNo)->first();
            }
        }

        return view('tickets.create', compact('analysis', 'ticket'));
    }

    public function store(
        Request $request,
        TicketParser $ticketParser,
        OllamaService $ollamaService
    ): RedirectResponse {
        set_time_limit(180);
        $request->validate([
            'raw_message' => ['required', 'string'],
        ], [
            'raw_message.required' => 'Silakan tempelkan alert Magnus terlebih dahulu.',
        ]);

        $rawMessage = $request->input('raw_message');

        // Ekstraksi informasi alert melalui TicketParser.
        $data = $ticketParser->parse($rawMessage);

        $validator = Validator::make($data, [
            'no_ticket' => ['required', 'string', 'max:255'],
            'detection' => ['required', 'string', 'max:255'],
        ], [
            'no_ticket.required' => 'Nomor tiket tidak ditemukan dalam alert.',
            'detection.required' => 'Detection tidak ditemukan dalam alert.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        if (Ticket::where('no_ticket', $data['no_ticket'])->exists()) {
            return back()
                ->withErrors([
                    'raw_message' => 'Ticket ' . $data['no_ticket'] . ' sudah terdaftar.',
                ])
                ->withInput();
        }

        $eventTime = null;

        if (!empty($data['event_time'])) {
            try {
                $eventTime = Carbon::parse($data['event_time']);
            } catch (Throwable $e) {
                return back()
                    ->withErrors([
                        'raw_message' => 'Format Waktu Kejadian tidak dapat dibaca.',
                    ])
                    ->withInput();
            }
        }

        // AI harus berhasil menghasilkan struktur yang valid
        // sebelum tiket dan hasil analisis disimpan.
        try {
            $prompt = <<<PROMPT
Kamu adalah asisten analisis alert Security Operations Center (SOC).
Bantu engineer memahami alert dan menentukan langkah investigasi yang relevan.

ATURAN:
- Gunakan bahasa Indonesia yang jelas dan profesional.
- Gunakan informasi dalam alert dan pengetahuan umum keamanan yang relevan.
- Jangan mengarang fakta, hasil pemeriksaan, atau aktivitas yang belum terbukti.
- Bedakan indikasi dari fakta yang terkonfirmasi.
- Jangan menyimpulkan aktivitas pasti berbahaya hanya berdasarkan nama detection.
- Berikan rekomendasi yang aman dan perlu ditinjau engineer.
- Hindari pengulangan informasi yang sama.
- Keluarkan JSON valid saja, tanpa Markdown atau teks pembuka.
- Gunakan struktur JSON berikut secara persis:
{
  "summary": "Ringkasan alert dalam 2-3 kalimat",
  "attention_points": [
    "Maksimal 3 poin perhatian yang spesifik dan relevan"
  ],
  "handling_steps": [
    "Maksimal 4 langkah investigasi yang praktis dan aman"
  ]
}

ALERT:
{$rawMessage}
PROMPT;

            $aiResponse = $ollamaService->generate($prompt);

            // Mengantisipasi model membungkus JSON dengan code fence.
            $jsonText = preg_replace(
                '/\A\s*```(?:json)?\s*|\s*```\s*\z/i',
                '',
                trim($aiResponse)
            );

            $result = json_decode($jsonText, true, 512, JSON_THROW_ON_ERROR);

            if (
                !isset($result['summary'], $result['attention_points'], $result['handling_steps']) ||
                !is_string($result['summary']) ||
                !is_array($result['attention_points']) ||
                !is_array($result['handling_steps']) ||
                count($result['attention_points']) === 0 ||
                count($result['handling_steps']) === 0
            ) {
                throw new \RuntimeException('Struktur hasil AI tidak sesuai format.');
            }

            foreach (['attention_points', 'handling_steps'] as $key) {
                foreach ($result[$key] as $item) {
                    if (!is_string($item) || trim($item) === '') {
                        throw new \RuntimeException('Ada bagian hasil AI yang kosong atau tidak valid.');
                    }
                }
            }
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors([
                    'raw_message' => 'Analisis AI gagal menghasilkan jawaban yang valid. Silakan coba lagi.',
                ])
                ->withInput();
        }

        // Simpan tiket, analisis, dan checklist dalam satu transaksi.
        try {
            DB::transaction(function () use ($data, $rawMessage, $eventTime, $result) {
                Ticket::create([
                    'no_ticket' => $data['no_ticket'],
                    'offense_id' => $data['offense_id'] ?? null,
                    'detection' => $data['detection'],
                    'risk_level' => $data['risk_level'] ?? null,
                    'event_time' => $eventTime,
                    'status' => $data['status'] ?? 'Open',
                    'raw_message' => $rawMessage,
                    'inserted_at' => now(),
                    'id_user' => Auth::user()->id_user,
                ]);

                $analysis = AIAnalysis::create([
                    'no_ticket' => $data['no_ticket'],
                    'summary' => trim($result['summary']),
                    'attention_point' => implode(
                        "\n",
                        array_map('trim', $result['attention_points'])
                    ),
                ]);

                foreach (array_values($result['handling_steps']) as $index => $step) {
                    ActionChecklist::create([
                        'id_analysis' => $analysis->id_analysis,
                        'action' => trim($step),
                        'status' => 'Pending',
                        'action_order' => $index + 1,
                    ]);
                }
            });
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors([
                    'raw_message' => 'Tiket atau hasil analisis gagal disimpan. Periksa log Laravel sebelum mencoba kembali.',
                ])
                ->withInput();
        }

        $request->session()->put(
            'active_analysis_ticket_no',
            $data['no_ticket']
        );

        return redirect()->route('tickets.create', [
            'ticket' => $data['no_ticket'],
        ]);
    }

    public function index(Request $request): View
    {
        $query = Ticket::with('user');

        // Pencarian berdasarkan nomor tiket atau detection.
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('no_ticket', 'like', "%{$search}%")
                    ->orWhere('detection', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tanggal kejadian.
        if ($request->filled('event_date')) {
            $request->validate([
                'event_date' => ['date_format:Y-m-d'],
            ]);

            $eventDate = $request->input('event_date');

            $query->whereDate('event_time', $eventDate);
        }

        // Tampilkan 10 tiket per halaman.
        $tickets = $query
            ->orderByDesc('inserted_at')
            ->paginate(10)
            ->withQueryString();

        // Ambil daftar tahun unik dari event_time untuk modal Export Excel
        $years = Ticket::query()
            ->whereNotNull('event_time')
            ->selectRaw('YEAR(event_time) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('tickets.index', compact('tickets', 'years'));
    }

    public function export(Request $request)
    {
        $years = Ticket::query()
            ->whereNotNull('event_time')
            ->selectRaw('YEAR(event_time) as year')
            ->distinct()
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->all();

        $request->validate([
            'year' => [
                'required',
                'integer',
                \Illuminate\Validation\Rule::in($years),
            ],
            'month' => ['nullable', 'integer', 'between:1,12'],
        ]);

        $year = (int) $request->input('year');
        $month = $request->filled('month')
            ? (int) $request->input('month')
            : null;

        $fileName = 'SOCIA2_Tickets_' . $year;

        if ($month !== null) {
            $fileName .= '_' . sprintf('%02d', $month);
        }

        return Excel::download(
            new AllTicketsExport($year, $month),
            $fileName . '.xlsx'
        );
    }

    public function destroy(string $no_ticket): RedirectResponse
    {
        $ticket = Ticket::findOrFail($no_ticket);

        DB::transaction(function () use ($ticket) {
            $ticket->delete();
        });

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket berhasil dihapus.');
    }
}