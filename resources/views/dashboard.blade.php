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
            <div class="p-4 border-bottom bg-light-subtle">
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