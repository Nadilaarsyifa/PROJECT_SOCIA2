<?php

namespace App\Http\Controllers;

use App\Models\AIAnalysis;
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

        $checklists = $analysis->actionChecklists()
            ->get()
            ->keyBy('id_action');

        foreach ($validated['actions'] as $item) {
            if (!$checklists->has($item['id_action'])) {
                abort(403, 'Checklist tidak sesuai dengan tiket ini.');
            }
        }

        DB::transaction(function () use ($validated, $checklists) {
            foreach ($validated['actions'] as $item) {
                $checklists[$item['id_action']]->update([
                    'status' => $item['status'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
        });

        $completed = collect($validated['actions'])
            ->where('status', 'Completed')
            ->count();

        $total = count($validated['actions']);

        return redirect()
            ->route('tickets.create', ['ticket' => $analysis->no_ticket])
            ->with(
                'success',
                "Progres checklist tersimpan: {$completed} dari {$total} tindakan selesai."
            );
    }
}
