<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0F3B2C;">
                Dashboard
            </h2>
            <p class="text-secondary small mb-0">
                Ringkasan deteksi dan tingkat risiko SOC.
            </p>
        </div>
    </x-slot>

    @php
    $detectionColors = [
        '#17824A', '#2B6CB0', '#D69E2E', '#805AD5',
        '#DD6B20', '#319795', '#D53F8C', '#718096',
        '#38A169', '#667EEA', '#B7791F', '#4A5568',
    ];

    $riskColor = function ($label) {
        return match (strtolower(trim($label))) {
            'critical' => '#991B1B',
            'high' => '#DC2626',
            'medium' => '#EA8A16',
            'low' => '#17824A',
            'informational', 'info' => '#64748B',
            default => '#2B6CB0',
        };
    };

    $riskBadgeClass = function ($label) {
        return match (strtolower(trim($label ?? ''))) {
            'high', 'critical' => 'risk-high',
            'medium' => 'risk-medium',
            'low' => 'risk-low',
            'informational', 'info' => 'risk-info',
            default => 'risk-default',
        };
    };

    $detectionTotal = $detectionStats->sum('total');
    $riskTotal = $riskStats->sum('total');
    @endphp

    <style>
        /* Gaya disamakan dengan halaman All Tickets */
        .dashboard-page {
            color: #24352d;
        }

        .dashboard-page .dash-card {
            border: 1px solid #dce6df;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 59, 44, 0.04);
            overflow: hidden;
            background: #fff;
        }

        .dashboard-page .page-title {
            color: #0F3B2C;
        }

        .dashboard-page .form-control,
        .dashboard-page .form-select {
            border-color: #dce6df;
            min-height: 42px;
        }

        .dashboard-page .form-control:focus,
        .dashboard-page .form-select:focus {
            border-color: #6caa8a;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.1);
        }

        /* Total tiket */
        .dashboard-page .ticket-icon {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #edf5ef;
            color: #176b45;
            border-radius: 16px;
            font-size: 1.7rem;
            flex-shrink: 0;
        }

        .dashboard-page .ticket-number {
            color: #0F3B2C;
            font-weight: 700;
            font-size: 2rem;
            line-height: 1.15;
        }

        .dashboard-page .table-ticket-number {
            color: #0F3B2C;
            font-weight: 700;
        }

        /* Badge jumlah */
        .dashboard-page .count-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.8rem;
            font-weight: 700;
            white-space: nowrap;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        /* Chart */
        .dashboard-page .detection-chart-wrapper {
            position: relative;
            width: 100%;
            max-width: 280px;
            height: 280px;
            margin: 0 auto 1.25rem;
        }

        .dashboard-page .risk-chart-wrapper {
            position: relative;
            width: 100%;
            height: 250px;
            margin-bottom: 1.25rem;
        }

        /* Legend */
        .dashboard-page .detection-legend {
            max-height: 300px;
            overflow-y: auto;
        }

        .dashboard-page .legend-item,
        .dashboard-page .risk-item-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .dashboard-page .legend-item {
            padding: 10px 4px;
            border-bottom: 1px solid #e2e8f0;
        }

        .dashboard-page .legend-item:last-child {
            border-bottom: 0;
        }

        .dashboard-page .legend-item:hover {
            background: #f1f5f9;
        }

        .dashboard-page .legend-name {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 10px;
            color: #334155;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .dashboard-page .legend-name > span:last-child {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dashboard-page .legend-dot {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
            border-radius: 4px;
        }

        .dashboard-page .legend-value {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            gap: 12px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .dashboard-page .legend-value strong {
            color: #0F3B2C;
            font-size: 1rem;
            font-weight: 700;
        }

        .dashboard-page .legend-value span {
            min-width: 52px;
            text-align: right;
        }

        /* Risk */
        .dashboard-page .risk-legend {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .dashboard-page .risk-progress {
            height: 8px;
            margin-top: 8px;
            border-radius: 10px;
            background: #e2e8f0;
        }

        .dashboard-page .risk-progress .progress-bar {
            border-radius: 10px;
        }

        /* Kosong */
        .dashboard-page .empty-state {
            padding: 52px 20px;
            text-align: center;
        }

        .dashboard-page .empty-icon {
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

        /* Tabel pemantauan penanganan tiket (disamakan dengan All Tickets) */
        .dashboard-page .custom-table {
            border-color: #e2e8f0 !important;
        }

        .dashboard-page .custom-table thead th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .dashboard-page .custom-table tbody td {
            padding: 14px;
            border-color: #e2e8f0;
        }

        .dashboard-page .custom-table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .dashboard-page .risk-badge,
        .dashboard-page .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .dashboard-page .risk-high {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .dashboard-page .risk-medium {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .dashboard-page .risk-low {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .dashboard-page .risk-info {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .dashboard-page .risk-default {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .dashboard-page .status-pending {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .dashboard-page .status-ongoing {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .dashboard-page .status-completed {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .dashboard-page .handling-status {
            cursor: help;
        }

        @media (max-width: 767.98px) {
            .dashboard-page .ticket-number {
                font-size: 1.6rem;
            }

            .dashboard-page .detection-chart-wrapper {
                height: 230px;
                max-width: 230px;
            }

            .dashboard-page .risk-chart-wrapper {
                height: 220px;
            }
        }
    </style>

    <div class="dashboard-page">

        {{-- FILTER PERIODE --}}
        <div class="card dash-card mb-4">
            <div class="p-4 bg-light-subtle">
                <form action="{{ route('dashboard') }}" method="GET" id="dashboardFilter">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="period" class="form-label small fw-semibold text-dark">
                                Periode Data
                            </label>
                            <select name="period" id="period" class="form-select">
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>All — Semua Tiket</option>
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulanan</option>
                                <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Harian</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3" id="monthField">
                            <label for="month" class="form-label small fw-semibold text-dark">
                                Pilih Bulan
                            </label>
                            <input type="month"
                                name="month"
                                id="month"
                                value="{{ request('month', now()->format('Y-m')) }}"
                                class="form-control">
                        </div>

                        <div class="col-12 col-md-4 col-lg-3" id="dateField">
                            <label for="date" class="form-label small fw-semibold text-dark">
                                Pilih Tanggal
                            </label>
                            <input type="date"
                                name="date"
                                id="date"
                                value="{{ request('date', now()->format('Y-m-d')) }}"
                                class="form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TOTAL TIKET --}}
        <div class="card dash-card mb-4">
            <div class="p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="ticket-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-semibold">Total Tiket</div>
                        <div class="ticket-number">{{ number_format($totalTickets) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DIAGRAM --}}
        <div class="row g-4">

            {{-- KOMPOSISI DETEKSI --}}
            <div class="col-12 col-xl-6">
                <section class="card dash-card h-100">
                    <div class="p-4 border-bottom">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h5 class="page-title fw-bold mb-1">Komposisi Deteksi</h5>
                                <p class="text-secondary small mb-0">Jumlah tiket berdasarkan jenis Detection</p>
                            </div>
                            <span class="count-badge">{{ number_format($detectionTotal) }} Deteksi</span>
                        </div>
                    </div>

                    <div class="p-4">
                        @if ($detectionStats->isNotEmpty())
                        <div class="detection-chart-wrapper">
                            <canvas
                                id="detectionChart"
                                data-labels="{{ json_encode($detectionStats->pluck('label')->values(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                data-values="{{ json_encode($detectionStats->pluck('total')->values()) }}">
                            </canvas>
                        </div>

                        <div class="detection-legend">
                            @foreach ($detectionStats as $index => $item)
                            @php
                            $color = $detectionColors[$index % count($detectionColors)];
                            $percentage = $detectionTotal > 0 ? ($item->total / $detectionTotal) * 100 : 0;
                            @endphp

                            <div class="legend-item">
                                <div class="legend-name">
                                    <span class="legend-dot" data-color="{{ $color }}"></span>
                                    <span title="{{ $item->label }}">{{ $item->label }}</span>
                                </div>

                                <div class="legend-value">
                                    <strong>{{ number_format($item->total) }}</strong>
                                    <span>{{ number_format($percentage, 1, ',', '.') }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-pie-chart"></i></div>
                            <h6 class="fw-bold mt-3 mb-2">Belum ada data Detection</h6>
                            <p class="text-secondary small mb-0">Data akan muncul setelah tiket tersedia.</p>
                        </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- DISTRIBUSI RISK LEVEL --}}
            <div class="col-12 col-xl-6">
                <section class="card dash-card h-100">
                    <div class="p-4 border-bottom">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h5 class="page-title fw-bold mb-1">Distribusi Tingkat Risiko</h5>
                                <p class="text-secondary small mb-0">Jumlah tiket berdasarkan Risk Level</p>
                            </div>
                            <span class="count-badge">{{ number_format($riskTotal) }} Tiket</span>
                        </div>
                    </div>

                    <div class="p-4">
                        @if ($riskStats->isNotEmpty())
                        <div class="risk-chart-wrapper">
                            <canvas
                                id="riskChart"
                                data-labels="{{ json_encode($riskStats->pluck('label')->values(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
                                data-values="{{ json_encode($riskStats->pluck('total')->values()) }}">
                            </canvas>
                        </div>

                        <div class="risk-legend">
                            @foreach ($riskStats as $item)
                            @php
                            $color = $riskColor($item->label);
                            $percentage = $riskTotal > 0 ? ($item->total / $riskTotal) * 100 : 0;
                            @endphp

                            <div class="risk-item">
                                <div class="risk-item-heading">
                                    <div class="legend-name">
                                        <span class="legend-dot" data-color="{{ $color }}"></span>
                                        <span>{{ $item->label }}</span>
                                    </div>

                                    <div class="legend-value">
                                        <strong>{{ number_format($item->total) }}</strong>
                                        <span>{{ number_format($percentage, 1, ',', '.') }}%</span>
                                    </div>
                                </div>

                                <div class="progress risk-progress">
                                    <div class="progress-bar"
                                        role="progressbar"
                                        data-width="{{ $percentage }}"
                                        data-color="{{ $color }}"
                                        aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-bar-chart"></i></div>
                            <h6 class="fw-bold mt-3 mb-2">Belum ada data Risk Level</h6>
                            <p class="text-secondary small mb-0">Data akan muncul setelah tiket tersedia.</p>
                        </div>
                        @endif
                    </div>
                </section>
            </div>

        </div>

        {{-- TABEL PEMANTAUAN PENANGANAN TIKET --}}
        <div class="card dash-card mt-4">
            <div class="p-4 border-bottom">
                <div>
                    <h5 class="page-title fw-bold mb-1">
                        Pemantauan Penanganan Tiket
                    </h5>
                    <p class="text-secondary small mb-0">
                        Progres penanganan tiket berdasarkan periode yang dipilih.
                    </p>
                </div>
            </div>

            @if ($handlingTickets->isNotEmpty())
                <div class="table-responsive p-4">
                    <table class="table table-bordered custom-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th class="text-center" style="width: 140px;">Ticket No.</th>
                                <th>Detection</th>
                                <th class="text-center" style="width: 120px;">Risk Level</th>
                                <th class="text-center" style="width: 150px;">Event Time</th>
                                <th class="text-center" style="width: 180px;">Status Penanganan</th>
                                <th class="text-center" style="width: 110px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($handlingTickets as $index => $ticket)
                                @php
                                    $statusClass = match ($ticket->handling_status) {
                                        'Belum Ditangani' => 'status-pending',
                                        'Sedang Ditangani' => 'status-ongoing',
                                        'Selesai' => 'status-completed',
                                        default => 'status-pending',
                                    };

                                    $handlerName = $ticket->handler?->nama;
                                @endphp

                                <tr>
                                    <td class="text-center fw-semibold text-secondary">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="text-center">
                                        <span class="table-ticket-number">
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
                                        <span class="risk-badge {{ $riskBadgeClass($ticket->risk_level) }}">
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
                                        <span
                                            class="status-badge handling-status {{ $statusClass }}"
                                            @if ($ticket->handling_status === 'Sedang Ditangani' && $handlerName)
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Ditangani oleh: {{ $handlerName }}"
                                            @endif
                                        >
                                            {{ $ticket->handling_status }}
                                        </span>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <a href="{{ route('tickets.create', ['ticket' => $ticket->no_ticket]) }}"
                                            class="btn btn-info text-white fw-medium d-inline-flex flex-column align-items-center justify-content-center p-2 rounded-3"
                                            style="min-width: 65px; line-height: 1.2; font-size: 0.75rem; background-color: #00c4cc; border: none;"
                                            title="Lihat detail ticket">
                                            <i class="bi bi-eye-fill mb-1" style="font-size: 1.1rem;"></i>
                                            <span>Detail</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h6 class="fw-bold mt-3 mb-2">Belum ada tiket</h6>
                    <p class="text-secondary small mb-0">
                        Tidak ada tiket pada periode yang dipilih.
                    </p>
                </div>
            @endif
        </div>

    </div>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter periode otomatis
            const filterForm = document.getElementById('dashboardFilter');
            const period = document.getElementById('period');
            const monthField = document.getElementById('monthField');
            const dateField = document.getElementById('dateField');
            const monthInput = document.getElementById('month');
            const dateInput = document.getElementById('date');

            function updatePeriodFields() {
                monthField.style.display = period.value === 'month' ? '' : 'none';
                dateField.style.display = period.value === 'day' ? '' : 'none';
            }

            period.addEventListener('change', function() {
                updatePeriodFields();
                filterForm.requestSubmit();
            });

            monthInput.addEventListener('change', function() {
                if (period.value === 'month') filterForm.requestSubmit();
            });

            dateInput.addEventListener('change', function() {
                if (period.value === 'day') filterForm.requestSubmit();
            });

            updatePeriodFields();

            // Tooltip nama SOC Engineer pemegang tiket
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    new bootstrap.Tooltip(el);
                });
            }

            // Warna dan lebar elemen dinamis
            document.querySelectorAll('[data-color]').forEach(el => {
                el.style.backgroundColor = el.dataset.color;
            });

            document.querySelectorAll('[data-width]').forEach(el => {
                el.style.width = el.dataset.width + '%';
            });

            // Font chart mengikuti font halaman (sama dengan All Tickets)
            Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
            Chart.defaults.font.size = 13;
            Chart.defaults.color = '#334155';

            const detectionColors = [
                '#17824A', '#2B6CB0', '#D69E2E', '#805AD5',
                '#DD6B20', '#319795', '#D53F8C', '#718096',
                '#38A169', '#667EEA', '#B7791F', '#4A5568'
            ];

            const tooltipBase = {
                padding: 12,
                titleFont: { size: 14, weight: '600' },
                bodyFont: { size: 14 }
            };

            // Doughnut Detection
            const detectionCanvas = document.getElementById('detectionChart');

            if (detectionCanvas) {
                const labels = JSON.parse(detectionCanvas.dataset.labels || '[]');
                const values = JSON.parse(detectionCanvas.dataset.values || '[]');

                if (labels.length > 0) {
                    new Chart(detectionCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: labels.map((_, i) => detectionColors[i % detectionColors.length]),
                                borderColor: '#ffffff',
                                borderWidth: 3,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '68%',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    ...tooltipBase,
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((s, v) => s + v, 0);
                                            const pct = total > 0 ? (context.raw / total * 100).toFixed(1) : 0;
                                            return ` ${context.label}: ${context.raw} (${pct}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Bar Risk Level
            function getRiskColor(label) {
                const colors = {
                    critical: '#991B1B',
                    high: '#DC2626',
                    medium: '#EA8A16',
                    low: '#17824A',
                    informational: '#64748B',
                    info: '#64748B'
                };
                return colors[label.trim().toLowerCase()] || '#2B6CB0';
            }

            const riskCanvas = document.getElementById('riskChart');

            if (riskCanvas) {
                const labels = JSON.parse(riskCanvas.dataset.labels || '[]');
                const values = JSON.parse(riskCanvas.dataset.values || '[]');

                if (labels.length > 0) {
                    new Chart(riskCanvas, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Tiket',
                                data: values,
                                backgroundColor: labels.map(getRiskColor),
                                borderRadius: 6,
                                barThickness: 26,
                                maxBarThickness: 28
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    ...tooltipBase,
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((s, v) => s + v, 0);
                                            const pct = total > 0 ? (context.raw / total * 100).toFixed(1) : 0;
                                            return ` ${context.raw} tiket (${pct}%)`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: { precision: 0, font: { size: 13 } },
                                    grid: { color: '#e2e8f0' }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: { font: { size: 14, weight: '600' } }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
</x-app-layout>