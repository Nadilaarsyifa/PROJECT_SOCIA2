<?php

namespace App\Http\Controllers;

use App\Models\AIAnalysis;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ActionChecklistController extends Controller
{
    public function update(Request $request, string $ticketNo): RedirectResponse
    {
        $analysis = AIAnalysis::where('no_ticket', $ticketNo)->firstOrFail();

        $validated = $request->validate([
            'actions' => ['required', 'array'],
            'actions.*.id_action' => ['required', 'integer', 'distinct'],
            'actions.*.status' => ['required', Rule::in(['Pending', 'Completed'])],
            'actions.*.notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $userId = $request->user()->id_user;

        $progress = DB::transaction(function () use ($validated, $analysis, $ticketNo, $userId) {

            // Kunci tiket agar dua pengguna tidak mengambil penanganan bersamaan.
            $ticket = Ticket::where('no_ticket', $ticketNo)
                ->lockForUpdate()
                ->firstOrFail();

            // Jika sudah ada penanggung jawab, hanya dia yang boleh mengubah checklist.
            if (
                $ticket->handled_by !== null &&
                (int) $ticket->handled_by !== (int) $userId
            ) {
                abort(403, 'Tiket ini sedang ditangani oleh SOC Engineer lain. Anda hanya dapat melihat checklist.');
            }

            // Ambil dan kunci checklist milik analisis tiket ini.
            $checklists = $analysis->actionChecklists()
                ->lockForUpdate()
                ->get()
                ->keyBy('id_action');

            // Pastikan semua ID checklist yang dikirim memang milik tiket ini.
            foreach ($validated['actions'] as $item) {
                if (!$checklists->has($item['id_action'])) {
                    abort(403, 'Checklist tidak sesuai dengan tiket ini.');
                }
            }

            // Penanggung jawab ditentukan saat pertama kali ada perubahan
            // status dari Pending menjadi Completed.
            $firstCompletion = collect($validated['actions'])->contains(
                function ($item) use ($checklists) {
                    $currentAction = $checklists[$item['id_action']];

                    return $currentAction->status === 'Pending'
                        && $item['status'] === 'Completed';
                }
            );

            if ($ticket->handled_by === null && $firstCompletion) {
                $ticket->handled_by = $userId;
                $ticket->save();
            }

            // Simpan status dan catatan checklist.
            foreach ($validated['actions'] as $item) {
                $checklists[$item['id_action']]->update([
                    'status' => $item['status'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Hitung progres berdasarkan seluruh checklist tiket.
            $total = $checklists->count();

            $completed = $analysis->actionChecklists()
                ->where('status', 'Completed')
                ->count();

            return [
                'completed' => $completed,
                'total' => $total,
            ];
        });

        return redirect()
            ->route('tickets.create', ['ticket' => $ticketNo])
            ->with(
                'success',
                "Progres checklist tersimpan: {$progress['completed']} dari {$progress['total']} tindakan selesai."
            );
    }
}