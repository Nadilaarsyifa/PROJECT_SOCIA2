<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0" style="color: #0F3B2C;">Analyze Ticket</h2>

            <form action="{{ route('tickets.reset') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Reset Analisis
                </button>
            </form>
        </div>
    </x-slot>

    <style>
        .analysis-page {
            --soc-green: #17824A;
            --soc-dark: #0F3B2C;
            --soc-border: #E1E8E3;
            color: #24352B;
        }

        /* Semua kartu (input + hasil) selalu selebar kolom. */
        .analysis-page .analysis-card {
            display: block;
            width: 100%;
            max-width: 100%;
            background: #fff;
            border: 1px solid var(--soc-border);
            border-radius: 16px;
            box-shadow: 0 3px 12px rgba(15, 59, 44, .04);
        }

        /* Tinggi kartu hasil mengikuti isi: kurus saat kosong, memanjang saat ada isi. */
        .analysis-page .result-card .card-body {
            padding: 16px 24px !important;
        }

        .analysis-page .result-card .result-content:empty {
            display: none;
        }

        .analysis-page .result-card:has(.result-content:empty) .result-head {
            margin-bottom: 0 !important;
        }

        .analysis-page .section-title {
            margin: 0;
            color: var(--soc-dark);
            font-size: 16px;
            font-weight: 700;
        }

        .analysis-page .section-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 12px;
            background: #E8F4EC;
            color: var(--soc-green);
            font-size: 20px;
        }

        .analysis-page .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 11px;
            border-radius: 20px;
            background: #E8F4EC;
            color: var(--soc-green);
            font-size: 12px;
            font-weight: 700;
        }

        .analysis-page .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        .analysis-page .alert-input {
            display: block;
            width: 100%;
            min-height: 220px;
            padding: 14px 16px;
            resize: vertical;
            border: 1px solid #D7E1DA;
            border-radius: 12px;
            background: #FBFCFB;
            color: #24352B;
            font-family: Consolas, monospace;
            font-size: 13px;
            line-height: 1.7;
            transition: border-color .2s, box-shadow .2s;
        }

        .analysis-page .alert-input:focus {
            border-color: var(--soc-green);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(23, 130, 74, .1);
            outline: none;
        }

        .analysis-page .btn-soc {
            padding: 11px 20px;
            border: 1px solid var(--soc-green);
            border-radius: 10px;
            background: var(--soc-green);
            color: #fff;
            font-weight: 600;
        }

        .analysis-page .btn-soc:hover:not(:disabled) {
            border-color: #10683B;
            background: #10683B;
            color: #fff;
        }

        .analysis-page .btn-soc:disabled {
            cursor: not-allowed;
            opacity: .55;
        }

        .analysis-page .empty-state {
            padding: 8px 0;
            color: #718078;
            font-size: 14px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .analysis-page .result-content {
            width: 100%;
            overflow-wrap: anywhere;
        }

        @media (max-width: 576px) {
            .analysis-page .card-body {
                padding: 18px !important;
            }

            .analysis-page .btn-soc {
                width: 100%;
            }
        }
    </style>

    <div class="analysis-page container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xxl-10">

                {{-- Input Alert --}}
                <section class="analysis-card input-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="section-icon">
                                    <i class="bi bi-activity" aria-hidden="true"></i>
                                </div>
                                <h5 class="section-title">Analyze New Ticket</h5>
                            </div>

                            <span class="status-pill">
                                <span class="status-dot" aria-hidden="true"></span>
                                Input Alert
                            </span>
                        </div>

                        {{-- Pesan Validasi Error --}}
                        @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form id="alertForm" action="{{ route('tickets.store') }}" method="POST">
                            @csrf

                            <textarea
                                id="raw_message"
                                name="raw_message"
                                class="alert-input"
                                placeholder="Tempelkan pesan alert di sini..."
                                required>{{ old('raw_message', $ticket?->raw_message) }}</textarea>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 mb-4">
                                <small class="text-secondary" id="characterCount" aria-live="polite">0 karakter</small>
                            </div>

                            <button type="submit" id="analyzeButton" class="btn btn-soc" disabled>
                                Analisis Alert
                            </button>
                        </form>
                    </div>
                </section>

                {{-- Hasil analisis: tiga kartu, tersusun satu per satu, lebar penuh --}}
                <div class="d-flex flex-column align-items-stretch gap-4 mb-4">

                    {{-- Ringkasan Alert --}}
                    <section class="analysis-card result-card">
                        <div class="card-body p-4">
                            <div class="result-head d-flex align-items-center gap-3 mb-3">
                                <div class="section-icon">
                                    <i class="bi bi-card-text" aria-hidden="true"></i>
                                </div>
                                <h5 class="section-title">Ringkasan Alert</h5>
                            </div>

                            @if ($analysis)
                            <div class="result-content mt-3">
                                <p class="mb-0" style="white-space: pre-line;">{{ $analysis->summary }}</p>
                            </div>
                            @else
                            @endif

                        </div>
                    </section>

                    {{-- Poin Perhatian --}}
                    <section class="analysis-card result-card">
                        <div class="card-body p-4">
                            <div class="result-head d-flex align-items-center gap-3 mb-3">
                                <div class="section-icon">
                                    <i class="bi bi-exclamation-triangle" aria-hidden="true"></i>
                                </div>
                                <h5 class="section-title">Poin Perhatian</h5>
                            </div>

                            @if ($analysis && $analysis->attention_point)
                            <ul class="result-content mt-3 mb-0 ps-3">
                                @foreach (preg_split('/\r\n|\r|\n/', $analysis->attention_point) as $point)
                                @if (trim($point) !== '')
                                <li class="mb-2">{{ trim($point) }}</li>
                                @endif
                                @endforeach
                            </ul>
                            @else
                            @endif

                        </div>
                    </section>

                    {{-- Langkah Penanganan --}}
                    <section class="analysis-card result-card">
                        <div class="card-body p-4">
                            <div class="result-head d-flex align-items-center gap-3 mb-3">
                                <div class="section-icon">
                                    <i class="bi bi-list-check" aria-hidden="true"></i>
                                </div>
                                <h5 class="section-title">Langkah Penanganan</h5>
                            </div>

                            @if (session('success'))
                            <div class="alert alert-success mt-3" role="alert">
                                {{ session('success') }}
                            </div>
                            @endif

                            @if ($analysis &&$analysis->actionChecklists->isNotEmpty())
                            <p class="text-secondary small mt-3 mb-0">
                                Tandai tindakan yang telah dilakukan. Catatan dapat diisi jika diperlukan, lalu simpan seluruh progres sekaligus.
                            </p>

                            <form
                                action="{{ route('action-checklists.update', $analysis->no_ticket) }}"
                                method="POST"
                                id="checklistForm">
                                @csrf
                                @method('PATCH')

                                <div class="d-flex flex-column gap-3 mt-3">
                                    @foreach ($analysis->actionChecklists as $index =>$action)
                                    <div class="border rounded-3 p-3" style="border-color: #E1E8E3 !important;">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="pt-1">
                                                {{-- Hidden status Pending ditaruh sebelum Checkbox agar nilai 'Pending' terkirim jika un-checked --}}
                                                <input
                                                    type="hidden"
                                                    name="actions[{{ $index }}][id_action]"
                                                    value="{{ $action->id_action }}">

                                                <input
                                                    type="hidden"
                                                    name="actions[{{ $index }}][status]"
                                                    value="Pending">

                                                <input
                                                    class="form-check-input action-checkbox mt-0"
                                                    type="checkbox"
                                                    name="actions[{{ $index }}][status]"
                                                    value="Completed"
                                                    id="action_{{ $action->id_action }}"
                                                    {{ $action->status === 'Completed' ? 'checked' : '' }}>
                                            </div>

                                            <div class="flex-grow-1">
                                                <label
                                                    for="action_{{ $action->id_action }}"
                                                    class="form-check-label fw-semibold cursor-pointer">
                                                    {{ $action->action }}
                                                </label>

                                                <div class="mt-2">
                                                    <button
                                                        class="btn btn-sm btn-outline-secondary"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#note_{{ $action->id_action }}"
                                                        aria-expanded="{{ $action->notes ? 'true' : 'false' }}"
                                                        aria-controls="note_{{ $action->id_action }}">
                                                        <i class="bi bi-pencil-square me-1"></i>
                                                        {{ $action->notes ? 'Edit Note' : 'Beri Note' }}
                                                    </button>
                                                </div>

                                                <div
                                                    class="collapse {{ $action->notes ? 'show' : '' }} mt-3"
                                                    id="note_{{ $action->id_action }}">
                                                    <label
                                                        for="notes_{{ $action->id_action }}"
                                                        class="form-label small fw-semibold">
                                                        Catatan Engineer (Opsional)
                                                    </label>

                                                    <textarea
                                                        class="form-control"
                                                        name="actions[{{ $index }}][notes]"
                                                        id="notes_{{ $action->id_action }}"
                                                        rows="3"
                                                        maxlength="5000"
                                                        placeholder="Tambahkan catatan jika diperlukan...">{{ $action->notes }}</textarea>
                                                </div>
                                            </div>

                                            <span class="badge {{ $action->status === 'Completed' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $action->status === 'Completed' ? 'Selesai' : 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                                    <small id="checklistProgress" class="text-secondary">
                                        0 dari {{ $analysis->actionChecklists->count() }} tindakan selesai
                                    </small>

                                    <button type="submit" class="btn btn-soc">
                                        Simpan Progres
                                    </button>
                                </div>
                            </form>
                            @elseif (!$analysis)
                            @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-clipboard-check fs-2"></i>
                            </div>
                            @endif
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Textarea Character Counter & Button Toggle
            const textarea = document.getElementById('raw_message');
            const counter = document.getElementById('characterCount');
            const analyzeButton = document.getElementById('analyzeButton');

            if (textarea && counter && analyzeButton) {
                function updateInputState() {
                    counter.textContent = textarea.value.length.toLocaleString('id-ID') + ' karakter';
                    analyzeButton.disabled = textarea.value.trim().length === 0;
                }

                textarea.addEventListener('input', updateInputState);
                updateInputState();
            }

            // Checklist Batch Progress & Confirmation
            const checklistForm = document.getElementById('checklistForm');

            if (checklistForm) {
                const checkboxes = checklistForm.querySelectorAll('.action-checkbox');
                const progress = document.getElementById('checklistProgress');

                function updateProgress() {
                    const completed = [...checkboxes].filter(item => item.checked).length;
                    progress.textContent = `${completed} dari ${checkboxes.length} tindakan selesai`;
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateProgress);
                });

                updateProgress();

                checklistForm.addEventListener('submit', function(event) {
                    const total = checkboxes.length;
                    const completed = [...checkboxes].filter(item => item.checked).length;
                    const remaining = total - completed;

                    if (remaining === 0) {
                        if (!confirm('Semua tindakan checklist telah ditandai selesai. Simpan progres ini?')) {
                            event.preventDefault();
                        }
                        return;
                    }

                    if (!confirm(`${remaining} dari ${total} tindakan belum dikerjakan. Apakah kamu ingin menyimpan progres ini?`)) {
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
</x-app-layout>