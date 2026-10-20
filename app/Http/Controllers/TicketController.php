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
Analisis alert berikut untuk membantu engineer melakukan investigasi.

ATURAN:
- Gunakan bahasa Indonesia yang jelas dan profesional.
- Gunakan hanya informasi yang tersedia dalam alert dan pengetahuan umum keamanan yang relevan.
- Jangan mengarang fakta, hasil pemeriksaan, atau aktivitas yang belum terbukti.
- Bedakan indikasi dengan fakta yang terkonfirmasi.
- Rekomendasi harus aman dan perlu ditinjau engineer.
- Jangan menganggap suatu aktivitas pasti berbahaya hanya berdasarkan nama detection.
- Keluarkan JSON valid saja, tanpa Markdown atau teks pembuka.
- Gunakan struktur JSON persis seperti ini:
{
  "summary": "Ringkasan alert",
  "attention_points": [
    "Poin perhatian pertama",
    "Poin perhatian kedua"
  ],
  "handling_steps": [
    "Langkah penanganan pertama",
    "Langkah penanganan kedua"
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

    public function index(): View
    {
        $tickets = Ticket::with('user')
            ->orderByDesc('inserted_at')
            ->get();

        return view('tickets.index', compact('tickets'));
    }
}
