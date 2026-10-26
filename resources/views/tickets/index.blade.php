<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                All Tickets
            </h2>
            <p class="text-secondary small mb-0">
                Daftar seluruh ticket alert keamanan SOC.
            </p>
        </div>
    </x-slot>

    <style>
        .tickets-page {
            color: #24352d;
        }

        .tickets-page .ticket-card {
            border: 1px solid #dce6df;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 59, 44, 0.04);
            overflow: hidden;
        }

        .tickets-page .page-title {
            color: #0F3B2C;
        }

        .tickets-page .form-control,
        .tickets-page .input-group-text {
            border-color: #dce6df;
            min-height: 42px;
        }

        .tickets-page .form-control:focus {
            border-color: #6caa8a;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1);
        }

        /* Desain Tabel Rapi Bergaris (Bordered Style) */
        .tickets-page .custom-table {
            border-color: #e2e8f0 !important;
        }

        .tickets-page .custom-table thead th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .tickets-page .custom-table tbody td {
            padding: 14px;
            border-color: #e2e8f0;
        }

        .tickets-page .custom-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .tickets-page .ticket-number {
            color: #0F3B2C;
            font-weight: 700;
        }

        .tickets-page .risk-badge,
        .tickets-page .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .tickets-page .risk-high {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .tickets-page .risk-medium {
            background: #fffbebfb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .tickets-page .risk-low {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .tickets-page .risk-info {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .tickets-page .risk-default {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .tickets-page .status-open,
        .tickets-page .status-investigation {
            background: #fefce8;
            color: #ca8a04;
            border: 1px solid #fef08a;
        }

        .tickets-page .status-confirmed {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .tickets-page .status-false-positive,
        .tickets-page .status-closed {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .tickets-page .status-default {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .tickets-page .btn-action-view {
            color: #0d9488;
            background-color: #ccfbf1;
            border: 1px solid #99f6e4;
            font-weight: 600;
        }

        .tickets-page .btn-action-view:hover {
            color: #fff;
            background-color: #0d9488;
        }

        .tickets-page .empty-state {
            padding: 52px 20px;
            text-align: center;
        }

        .tickets-page .empty-icon {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #edf5ef;
            color: #176b45;
            border-radius: 16px;
            font-size: 1.7rem;
        }
    </style>

    <div class="tickets-page">
        {{-- Notifikasi --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card ticket-card bg-white">
            <div class="card-body p-0">
                {{-- Judul dan tombol analisis --}}
                <div class="p-4 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="page-title fw-bold mb-1">
                                Daftar Ticket
                            </h5>
                            <p class="text-secondary small mb-0">
                                Cari dan tinjau alert keamanan yang tersimpan.
                            </p>
                        </div>

                        <a href="{{ route('tickets.create') }}" class="btn btn-success px-3 fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i>
                            Analisis Alert
                        </a>
                    </div>
                </div>

                {{-- Search otomatis dan filter tanggal --}}
                <div class="p-4 border-bottom bg-light-subtle">
                    <form id="ticket-filter-form" action="{{ route('tickets.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-md-6 col-lg-5">
                                <label for="search" class="form-label small fw-semibold text-dark">
                                    Cari Ticket
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search text-secondary"></i>
                                    </span>

                                    <input type="text"
                                        id="search"
                                        name="search"
                                        class="form-control"
                                        placeholder="Nomor tiket atau detection..."
                                        value="{{ request('search') }}"
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3">
                                <label for="event_date" class="form-label small fw-semibold text-dark">
                                    Tanggal Kejadian
                                </label>

                                <input type="date"
                                    id="event_date"
                                    name="event_date"
                                    class="form-control"
                                    value="{{ request('event_date') }}">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Informasi jumlah tiket biasa --}}
                <div class="px-4 py-3 bg-white">
                    <p class="text-secondary small mb-0">
                        Menampilkan <strong>{{ $tickets->firstItem() ?? 0 }} – {{ $tickets->lastItem() ?? 0 }}</strong> dari <strong>{{ $tickets->total() }}</strong> ticket
                    </p>
                </div>

                {{-- Tabel tiket dengan style bergaris --}}
                <div class="table-responsive p-4 pt-0">
                    <table class="table table-bordered custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th class="text-center" style="width: 140px;">Ticket No.</th>
                                <th>Detection</th>
                                <th class="text-center" style="width: 120px;">Risk Level</th>
                                <th class="text-center" style="width: 150px;">Event Time</th>
                                <th class="text-center" style="width: 110px;">Status</th>
                                <th class="text-center" style="width: 130px;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($tickets as $index => $ticket)
                            @php
                            $risk = strtolower(trim($ticket->risk_level ?? ''));
                            $riskClass = match ($risk) {
                            'high', 'critical' => 'risk-high',
                            'medium' => 'risk-medium',
                            'low' => 'risk-low',
                            'informational', 'info' => 'risk-info',
                            default => 'risk-default',
                            };

                            $status = strtolower(trim($ticket->status ?? ''));
                            $statusClass = match ($status) {
                            'open', 'needs investigation' => 'status-open',
                            'confirmed', 'valid incident' => 'status-confirmed',
                            'false positive' => 'status-false-positive',
                            'closed', 'resolved' => 'status-closed',
                            default => 'status-default',
                            };
                            @endphp

                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $tickets->firstItem() + $index }}
                                </td>

                                <td class="text-center">
                                    <span class="ticket-number">
                                        {{ $ticket->no_ticket }}
                                    </span>
                                    @if (!empty($ticket->offense_id))
                                    <div class="text-muted small">
                                        Offense: {{ $ticket->offense_id }}
                                    </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="fw-semibold text-dark">
                                        {{ $ticket->detection ?: '—' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="risk-badge {{ $riskClass }}">
                                        {{ $ticket->risk_level ?: 'Unknown' }}
                                    </span>
                                </td>

                                <td class="text-center text-nowrap">
                                    @if ($ticket->event_time)
                                    <div class="fw-semibold text-dark">
                                        {{ \Carbon\Carbon::parse($ticket->event_time)->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($ticket->event_time)->format('H:i:s') }}
                                    </div>
                                    @else
                                    <span class="text-secondary">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $ticket->status ?: 'Unknown' }}
                                    </span>
                                </td>

                                <td class="text-center text-nowrap">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('tickets.create', ['ticket' => $ticket->no_ticket]) }}"
                                            class="btn btn-sm btn-action-view"
                                            title="Lihat detail ticket">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>

                                        @if (auth()->user()?->hasRole('Administrator'))
                                        <form action="{{ route('tickets.destroy', $ticket->no_ticket) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin ingin menghapus ticket {{ $ticket->no_ticket }}?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Hapus ticket">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h6 class="fw-bold mt-3 mb-2">Ticket tidak ditemukan</h6>
                                        <p class="text-secondary small mb-3">
                                            @if (request()->filled('search') || request()->filled('event_date'))
                                            Coba ubah kata kunci atau pilih tanggal lain.
                                            @else
                                            Belum ada ticket yang tersimpan di database.
                                            @endif
                                        </p>
                                        @if (request()->filled('search') || request()->filled('event_date'))
                                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-success btn-sm">
                                            Reset Filter
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($tickets->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 p-4 border-top">
                    <span class="text-secondary small">
                        Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}
                    </span>
                    {{ $tickets->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('ticket-filter-form');
            const searchInput = document.getElementById('search');
            const dateInput = document.getElementById('event_date');

            if (!form || !searchInput || !dateInput) return;

            let searchTimeout;

            const shouldFocusSearch = sessionStorage.getItem('ticketSearchFocus') === 'true';
            const savedCursor = sessionStorage.getItem('ticketSearchCursor');

            if (shouldFocusSearch) {
                searchInput.focus({
                    preventScroll: true
                });
                if (savedCursor !== null) {
                    const position = Math.min(Number(savedCursor), searchInput.value.length);
                    searchInput.setSelectionRange(position, position);
                }
                sessionStorage.removeItem('ticketSearchFocus');
                sessionStorage.removeItem('ticketSearchCursor');
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    sessionStorage.setItem('ticketSearchFocus', 'true');
                    sessionStorage.setItem('ticketSearchCursor', searchInput.selectionStart);
                    form.requestSubmit();
                }, 600);
            });

            dateInput.addEventListener('change', function() {
                clearTimeout(searchTimeout);
                form.requestSubmit();
            });
        });
    </script>
</x-app-layout>