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
            border: 1px solid #e5ece7;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 59, 44, 0.04);
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

        .tickets-page .table thead th {
            background: #f5f8f6;
            color: #52645a;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.035em;
            padding: 15px 16px;
            white-space: nowrap;
            border-bottom: 1px solid #e5ece7;
        }

        .tickets-page .table tbody td {
            padding: 16px;
            vertical-align: middle;
            border-color: #edf1ee;
        }

        .tickets-page .ticket-number {
            color: #0F3B2C;
            font-weight: 700;
        }

        .tickets-page .risk-badge,
        .tickets-page .status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .tickets-page .risk-high {
            background: #fde8e7;
            color: #b42318;
        }

        .tickets-page .risk-medium {
            background: #fff2d6;
            color: #946200;
        }

        .tickets-page .risk-low {
            background: #e4f5e9;
            color: #187443;
        }

        .tickets-page .risk-info {
            background: #e7f0fb;
            color: #245da8;
        }

        .tickets-page .risk-default {
            background: #eef1ef;
            color: #59665e;
        }

        .tickets-page .status-open,
        .tickets-page .status-investigation {
            background: #fff2d6;
            color: #946200;
        }

        .tickets-page .status-confirmed {
            background: #fde8e7;
            color: #b42318;
        }

        .tickets-page .status-false-positive,
        .tickets-page .status-closed {
            background: #e4f5e9;
            color: #187443;
        }

        .tickets-page .status-default {
            background: #eef1ef;
            color: #59665e;
        }

        .tickets-page .btn-view {
            color: #176b45;
            background: #e9f5ed;
            border: 1px solid #d5ebdc;
        }

        .tickets-page .btn-view:hover {
            color: #fff;
            background: #176b45;
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

        .tickets-page .pagination {
            margin-bottom: 0;
        }

        .tickets-page .page-link {
            color: #176b45;
        }

        .tickets-page .page-item.active .page-link {
            background: #176b45;
            border-color: #176b45;
            color: #fff;
        }
    </style>

    <div class="tickets-page">
        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4"
                role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
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
                    <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3">
                        <div>
                            <h5 class="page-title fw-bold mb-1">
                                Daftar Ticket
                            </h5>
                            <p class="text-secondary small mb-0">
                                Cari dan tinjau alert keamanan yang tersimpan.
                            </p>
                        </div>

                        <a href="{{ route('tickets.create') }}"
                            class="btn btn-success px-3">
                            <i class="bi bi-plus-lg me-1"></i>
                            Analisis Alert
                        </a>
                    </div>
                </div>

                {{-- Search dan filter tanggal --}}
                <div class="p-4 border-bottom">
                    <form action="{{ route('tickets.index') }}"
                        method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-12 col-lg-6">
                                <label for="search"
                                    class="form-label small fw-semibold">
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
                                        value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-12 col-sm-7 col-lg-3">
                                <label for="event_date"
                                    class="form-label small fw-semibold">
                                    Tanggal Kejadian
                                </label>

                                <input type="date"
                                    id="event_date"
                                    name="event_date"
                                    class="form-control"
                                    value="{{ request('event_date') }}">
                            </div>

                            <div class="col-12 col-sm-5 col-lg-3">
                                <div class="d-flex gap-2">
                                    <button type="submit"
                                        class="btn btn-success flex-grow-1">
                                        <i class="bi bi-search me-1"></i>
                                        Cari
                                    </button>

                                    <a href="{{ route('tickets.index') }}"
                                        class="btn btn-outline-secondary"
                                        title="Reset pencarian dan tanggal"
                                        aria-label="Reset pencarian dan tanggal">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Informasi jumlah tiket --}}
                <div class="px-4 pt-3 pb-2">
                    <p class="text-secondary small mb-0">
                        Menampilkan
                        <strong>{{ $tickets->firstItem() ?? 0 }}</strong>
                        –
                        <strong>{{ $tickets->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $tickets->total() }}</strong>
                        ticket
                    </p>
                </div>

                {{-- Tabel tiket --}}
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Ticket No.</th>
                                <th>Detection</th>
                                <th>Risk Level</th>
                                <th>Event Time</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
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
                                    <td class="text-secondary small">
                                        {{ $tickets->firstItem() + $index }}
                                    </td>

                                    <td>
                                        <span class="ticket-number">
                                            {{ $ticket->no_ticket }}
                                        </span>
                                        @if (!empty($ticket->offense_id))
                                            <div class="text-secondary small mt-1">
                                                Offense: {{ $ticket->offense_id }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            {{ $ticket->detection ?: '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="risk-badge {{ $riskClass }}">
                                            {{ $ticket->risk_level ?: 'Unknown' }}
                                        </span>
                                    </td>

                                    <td class="text-nowrap">
                                        @if ($ticket->event_time)
                                            <div class="fw-medium">
                                                {{ \Carbon\Carbon::parse($ticket->event_time)->format('d M Y') }}
                                            </div>
                                            <div class="text-secondary small">
                                                {{ \Carbon\Carbon::parse($ticket->event_time)->format('H:i:s') }}
                                            </div>
                                        @else
                                            <span class="text-secondary">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $ticket->status ?: 'Unknown' }}
                                        </span>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('tickets.create', ['ticket' => $ticket->no_ticket]) }}"
                                            class="btn btn-sm btn-view"
                                            title="Lihat detail ticket"
                                            aria-label="Lihat detail ticket {{ $ticket->no_ticket }}">
                                            <i class="bi bi-eye me-1"></i>
                                            View
                                        </a>

                                        @if (auth()->user()?->hasRole('Administrator'))
                                            <form action="{{ route('tickets.destroy', $ticket->no_ticket) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus ticket {{ $ticket->no_ticket }}? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus ticket">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                disabled
                                                title="Hanya Administrator yang dapat menghapus ticket">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="bi bi-inbox"></i>
                                            </div>

                                            <h6 class="fw-bold mt-3 mb-2">
                                                Ticket tidak ditemukan
                                            </h6>

                                            <p class="text-secondary small mb-3">
                                                @if (request()->filled('search') || request()->filled('event_date'))
                                                    Coba ubah kata kunci atau pilih tanggal lain.
                                                @else
                                                    Belum ada ticket yang tersimpan di database.
                                                @endif
                                            </p>

                                            @if (request()->filled('search') || request()->filled('event_date'))
                                                <a href="{{ route('tickets.index') }}"
                                                    class="btn btn-outline-success btn-sm">
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
                    <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3 p-4 border-top">
                        <span class="text-secondary small">
                            Halaman {{ $tickets->currentPage() }}
                            dari {{ $tickets->lastPage() }}
                        </span>

                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
