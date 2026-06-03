@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<style>
/* ─── ADMIN LAYOUT ──────────────────────────────── */
.admin-wrapper {
    display: flex;
    min-height: calc(100vh - 64px);
    background: var(--cream);
}

/* ── Sidebar ── */
.admin-sidebar {
    width: 220px;
    flex-shrink: 0;
    background: var(--green-dark);
    padding: 2rem 0;
    position: sticky;
    top: 64px;
    height: calc(100vh - 64px);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.sidebar-label {
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    padding: 0 1.25rem;
    margin-bottom: .4rem;
    margin-top: 1.25rem;
}
.sidebar-label:first-child { margin-top: 0; }

.sidebar-link {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .65rem 1.25rem;
    color: rgba(255,255,255,.70);
    text-decoration: none;
    font-size: .875rem;
    font-weight: 500;
    border-left: 3px solid transparent;
    transition: all .18s;
}
.sidebar-link:hover {
    color: var(--white);
    background: rgba(255,255,255,.08);
}
.sidebar-link.active {
    color: var(--white);
    border-left-color: var(--brown-accent);
    background: rgba(255,255,255,.10);
    font-weight: 700;
}
.sidebar-link i { font-size: 1rem; width: 18px; text-align: center; }

.sidebar-footer {
    margin-top: auto;
    padding: 1.25rem;
    border-top: 1px solid rgba(255,255,255,.10);
}
.sidebar-footer a {
    display: flex;
    align-items: center;
    gap: .5rem;
    color: rgba(255,255,255,.55);
    font-size: .8rem;
    text-decoration: none;
    transition: color .2s;
}
.sidebar-footer a:hover { color: #FC8181; }

/* ── Main content ── */
.admin-main {
    flex: 1;
    overflow-x: hidden;
    min-width: 0;
}

.admin-topbar {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1.25rem 2rem;
    background: var(--white);
    border-bottom: 1px solid var(--cream-dark);
    box-shadow: var(--shadow-sm);
}
.admin-topbar .admin-title {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--green-dark);
    margin-left: auto;
}

.admin-content {
    padding: 2rem;
}

/* ─── STAT CARDS ─────────────────────────────────── */
.stat-cards-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.stat-card-admin {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--cream-dark);
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    transition: box-shadow .2s;
}
.stat-card-admin:hover { box-shadow: var(--shadow-md); }
.stat-card-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
.stat-card-icon.green  { background: var(--green-pale);                color: var(--green-mid); }
.stat-card-icon.brown  { background: rgba(224,123,58,.12);             color: var(--brown-accent); }
.stat-card-icon.dark   { background: rgba(30,58,31,.08);               color: var(--green-dark); }
.stat-card-body {}
.stat-card-label {
    font-size: .75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-muted);
    margin-bottom: .3rem;
}
.stat-card-num {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 600;
    color: var(--green-dark);
    line-height: 1;
    margin-bottom: .25rem;
}
.stat-card-sub {
    font-size: .775rem;
    color: var(--text-muted);
}
.stat-card-sub strong { color: var(--green-dark); }

/* ─── CHART + LEGEND SECTION ─────────────────────── */
.chart-section {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--cream-dark);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}
.chart-section-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    color: var(--green-dark);
    font-weight: 600;
    margin-bottom: 1.5rem;
}
.chart-wrap {
    display: flex;
    align-items: center;
    gap: 2.5rem;
    flex-wrap: wrap;
}
.chart-canvas-wrap {
    width: 160px; height: 160px;
    flex-shrink: 0;
}
.chart-legend {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: .6rem;
    min-width: 180px;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-size: .875rem;
}
.legend-dot {
    width: 12px; height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}
.legend-label { color: var(--text-muted); }
.legend-pct {
    margin-left: auto;
    font-weight: 700;
    color: var(--green-dark);
}

/* ─── TABLE SECTION ──────────────────────────────── */
.table-section {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--cream-dark);
    overflow: hidden;
}
.table-section-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
}
.table-section-header h2 {
    font-family: var(--font-display);
    font-size: 1.05rem;
    color: var(--green-dark);
    font-weight: 600;
    margin: 0;
}

/* Table styles */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .875rem;
}
.admin-table thead th {
    background: var(--green-dark);
    color: var(--white);
    padding: .85rem 1rem;
    font-weight: 700;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
    text-align: left;
}
.admin-table thead th:first-child { padding-left: 1.5rem; border-radius: 0; }
.admin-table thead th:last-child  { padding-right: 1.5rem; text-align: center; }

.sort-btn {
    background: none;
    border: none;
    color: rgba(255,255,255,.65);
    cursor: pointer;
    padding: 0 .25rem;
    font-size: .75rem;
    vertical-align: middle;
    transition: color .18s;
}
.sort-btn:hover { color: var(--white); }
.sort-btn.active { color: var(--brown-accent); }

.admin-table tbody tr {
    border-bottom: 1px solid var(--cream-dark);
    transition: background .15s;
}
.admin-table tbody tr:last-child { border-bottom: none; }
.admin-table tbody tr:hover { background: var(--cream); }

.admin-table td {
    padding: .9rem 1rem;
    vertical-align: middle;
    color: var(--text-dark);
}
.admin-table td:first-child { padding-left: 1.5rem; }
.admin-table td:last-child  { padding-right: 1.5rem; text-align: center; }

.td-id {
    font-weight: 700;
    color: var(--text-muted);
    font-size: .8rem;
}
.td-title {
    font-weight: 600;
    color: var(--green-dark);
    max-width: 160px;
}
.td-category { color: var(--text-muted); font-size: .82rem; max-width: 130px; }
.td-date { color: var(--text-muted); font-size: .82rem; white-space: nowrap; }
.td-priority {
    font-weight: 700;
    color: var(--green-dark);
    text-align: center;
}

/* Priority color scale */
.prio-high   { color: #C53030; }
.prio-mid    { color: #D97706; }
.prio-low    { color: var(--green-mid); }

/* Status dropdown in table */
.status-select-wrap { position: relative; display: inline-flex; }
.status-select {
    appearance: none;
    border: none;
    border-radius: 50px;
    padding: .35em 2em .35em .85em;
    font-size: .775rem;
    font-weight: 700;
    cursor: pointer;
    font-family: var(--font-main);
    transition: all .2s;
    outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23555'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right .55em center;
    background-size: 8px;
}
.status-select.s-masuk         { background-color:#FEF3C7; color:#92400E; }
.status-select.s-diverifikasi  { background-color:#EDE9FE; color:#5B21B6; }
.status-select.s-ditindaklanjuti { background-color:#DBEAFE; color:#1E40AF; }
.status-select.s-selesai       { background-color:#D1FAE5; color:#065F46; }
.status-select.s-ditolak       { background-color:#FEE2E2; color:#991B1B; }

/* Aksi buttons */
.btn-detail-sm {
    background: var(--cream);
    border: 1.5px solid var(--cream-dark);
    color: var(--green-dark);
    border-radius: var(--radius-sm);
    padding: .35rem .85rem;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
}
.btn-detail-sm:hover {
    background: var(--green-dark);
    border-color: var(--green-dark);
    color: var(--white);
}

/* ─── TABLE FOOTER ───────────────────────────────── */
.table-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
    font-size: .8rem;
    color: var(--text-muted);
}
.btn-page {
    background: var(--cream);
    border: 1.5px solid var(--cream-dark);
    color: var(--green-dark);
    border-radius: var(--radius-sm);
    padding: .35rem .75rem;
    font-size: .8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
}
.btn-page:hover, .btn-page.active {
    background: var(--green-mid);
    border-color: var(--green-mid);
    color: var(--white);
}
.btn-page.disabled { opacity: .4; pointer-events: none; }

/* ─── FLASH TOAST ─────────────────────────────────── */
.admin-toast {
    position: fixed;
    bottom: 1.5rem; right: 1.5rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}
.toast-item {
    background: var(--white);
    border: 1px solid var(--cream-dark);
    border-radius: var(--radius-md);
    padding: .85rem 1.25rem;
    font-size: .875rem;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: .6rem;
    min-width: 260px;
    animation: slideInRight .35s ease;
}
.toast-item.success { border-left: 4px solid var(--green-mid); }
.toast-item.error   { border-left: 4px solid #E53E3E; }
.toast-item i { font-size: 1.1rem; }
.toast-item.success i { color: var(--green-mid); }
.toast-item.error i   { color: #E53E3E; }

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(40px); }
    to   { opacity: 1; transform: translateX(0); }
}

/* ─── RESPONSIVE ─────────────────────────────────── */
@media (max-width: 991px) {
    .admin-sidebar { display: none; }
    .admin-content { padding: 1rem; }
    .stat-cards-row { grid-template-columns: 1fr; }
    .admin-table { font-size: .78rem; }
}
@media (max-width: 576px) {
    .admin-content { padding: .75rem; }
    .chart-wrap { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@section('content')

{{-- FLASH TOASTS --}}
<div class="admin-toast">
    @if(session('success'))
    <div class="toast-item success" id="toast-success">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="toast-item error" id="toast-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
</div>

<div class="admin-wrapper">

    {{-- ── SIDEBAR ────────────────────────────────── --}}
    <aside class="admin-sidebar">
        <div class="sidebar-label">Menu</div>

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="sidebar-link">
            <i class="bi bi-folder2-open"></i> Kelola Laporan
        </a>

        <div class="sidebar-label">Lainnya</div>
        <a href="{{ route('home') }}" class="sidebar-link">
            <i class="bi bi-house-door"></i> Lihat Website
        </a>
    </aside>

    {{-- ── MAIN ───────────────────────────────────── --}}
    <div class="admin-main">

        <div class="admin-content">

            {{-- ── STAT CARDS ────────────────────────────── --}}
            @php
            $s = $stats ?? [
                'total'           => 12,
                'masuk'           => 3,
                'diverifikasi'    => 5,
                'ditindaklanjuti' => 7,
                'selesai'         => 12,
                'ditolak'         => 0,
            ];
            $proyek_aktif = ($s['diverifikasi'] ?? 0) + ($s['ditindaklanjuti'] ?? 0);
            @endphp

            <div class="stat-cards-row">
                {{-- Total Laporan --}}
                <div class="stat-card-admin">
                    <div class="stat-card-icon green">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-label">Total Laporan Warga</div>
                        <div class="stat-card-num">{{ $s['total'] }}</div>
                        <div class="stat-card-sub">
                            <strong>{{ $s['masuk'] }}</strong> belum diproses
                        </div>
                    </div>
                </div>

                {{-- Proyek Aktif --}}
                <div class="stat-card-admin">
                    <div class="stat-card-icon brown">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-label">Total Proyek Aktif</div>
                        <div class="stat-card-num">{{ $proyek_aktif }}</div>
                        <div class="stat-card-sub">
                            <strong>{{ $s['diverifikasi'] }}</strong> diverifikasi &middot;
                            <strong>{{ $s['ditindaklanjuti'] }}</strong> ditindaklanjuti
                        </div>
                    </div>
                </div>

                {{-- Laporan Selesai --}}
                <div class="stat-card-admin">
                    <div class="stat-card-icon dark">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-card-label">Total Laporan Selesai</div>
                        <div class="stat-card-num">{{ $s['selesai'] }}</div>
                        <div class="stat-card-sub">
                            dari <strong>{{ $s['total'] }}</strong> laporan total
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── CHART SECTION ─────────────────────────── --}}
            <div class="chart-section">
                <h2 class="chart-section-title">Statistik Laporan</h2>
                <div class="chart-wrap">
                    <div class="chart-canvas-wrap">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="chart-legend" id="chartLegend">
                        {{-- Rendered by JS --}}
                    </div>
                </div>
            </div>

            {{-- ── TABLE SECTION ──────────────────────────── --}}
            <div class="table-section">
                <div class="table-section-header">
                    <h2>Daftar Laporan Warga</h2>
                    <a href="{{ route('admin.laporan.index') }}"
                       style="font-size:.8rem; color:var(--green-mid); font-weight:700; text-decoration:none">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div style="overflow-x:auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>
                                    Tanggal Laporan
                                </th>
                                <th>
                                    Kategori
                                </th>
                                <th>
                                    Priority Score
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReports as $report)
                            @php
                            $prio = $report->priority_score;
                            $prioClass = $prio >= 70 ? 'prio-high' : ($prio >= 40 ? 'prio-mid' : 'prio-low');

                            $allowedTransitions = [
                                'masuk'           => ['diverifikasi', 'ditolak'],
                                'diverifikasi'    => ['ditindaklanjuti', 'ditolak'],
                                'ditindaklanjuti' => ['selesai'],
                                'selesai'         => [],
                                'ditolak'         => [],
                            ];
                            $nextStatuses = $allowedTransitions[$report->status] ?? [];
                            @endphp
                            <tr>
                                <td class="td-id">#{{ str_pad($report->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="td-title">{{ $report->title }}</td>
                                <td class="td-date">
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y') }}
                                </td>
                                <td class="td-category">
                                    {{ $report->category->name ?? '-' }}
                                </td>
                                <td class="td-priority">
                                    <span class="{{ $prioClass }}">{{ $prio }}</span>
                                </td>
                                <td>
                                    {{-- Status update dropdown --}}
                                    @if(count($nextStatuses) > 0)
                                        <form method="POST"
                                              action="{{ route('admin.laporan.status.update', $report->id) }}"
                                              id="statusForm{{ $report->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="status-select-wrap">
                                                <select name="status"
                                                        class="status-select s-{{ $report->status }}"
                                                        onchange="this.form.submit()"
                                                        data-current="{{ $report->status }}">
                                                    <option value="{{ $report->status }}" selected>
                                                        {{ ucfirst($report->status) }}
                                                    </option>
                                                    @foreach($nextStatuses as $next)
                                                    <option value="{{ $next }}">
                                                        {{ ucfirst($next) }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    @else
                                        <span class="badge-status badge-{{ $report->status }}"
                                              style="padding:.35em .75em; border-radius:50px; font-size:.75rem; font-weight:600">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.laporan.show', $report->id) }}"
                                       class="btn-detail-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Table footer --}}
                <div class="table-footer">
                    <span>Menampilkan <strong>{{ $recentReports->count() }}</strong> dari <strong>{{ $s['total'] }}</strong> Laporan</span>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.laporan.index') }}" class="btn-page">1</a>
                        <a href="{{ route('admin.laporan.index', ['page' => 2]) }}" class="btn-page">2</a>
                        <a href="{{ route('admin.laporan.index', ['page' => 2]) }}" class="btn-page">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>{{-- /admin-content --}}
    </div>{{-- /admin-main --}}
</div>{{-- /admin-wrapper --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── PIE CHART ─────────────────────────────────────────
    const stats = {
        masuk:           {{ $s['masuk']           ?? 3  }},
        diverifikasi:    {{ $s['diverifikasi']    ?? 5  }},
        ditindaklanjuti: {{ $s['ditindaklanjuti'] ?? 7  }},
        selesai:         {{ $s['selesai']         ?? 12 }},
    };

    const total = Object.values(stats).reduce((a,b) => a+b, 0);

    const chartColors = {
        masuk:           '#D97706',
        diverifikasi:    '#E0B84A',
        ditindaklanjuti: '#E8D07A',
        selesai:         '#2D5A2E',
    };

    const chartLabels = {
        masuk:           'Diterima',
        diverifikasi:    'Diverifikasi',
        ditindaklanjuti: 'Diproses',
        selesai:         'Selesai',
    };

    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(stats).map(k => chartLabels[k]),
                datasets: [{
                    data: Object.values(stats),
                    backgroundColor: Object.keys(stats).map(k => chartColors[k]),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverBorderWidth: 0,
                }]
            },
            options: {
                cutout: '62%',
                plugins: { legend: { display: false }, tooltip: { enabled: true } },
                animation: { animateRotate: true, duration: 900 },
            }
        });
    }

    // ── LEGEND ────────────────────────────────────────────
    const legendEl = document.getElementById('chartLegend');
    if (legendEl) {
        Object.entries(stats).forEach(([key, val]) => {
            const pct = total > 0 ? Math.round((val / total) * 100) : 0;
            legendEl.innerHTML += `
                <div class="legend-item">
                    <div class="legend-dot" style="background:${chartColors[key]}"></div>
                    <span class="legend-label">${chartLabels[key]}</span>
                    <span class="legend-pct">${pct}%</span>
                </div>`;
        });
    }

    // ── TOAST AUTO-DISMISS ────────────────────────────────
    ['toast-success', 'toast-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            setTimeout(() => {
                el.style.transition = 'opacity .5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }, 4000);
        }
    });

    // ── STATUS SELECT COLOR UPDATE ────────────────────────
    document.querySelectorAll('.status-select').forEach(sel => {
        sel.addEventListener('change', function() {
            // Remove all s-* classes and apply new one
            this.className = this.className.replace(/s-\S+/, '');
            this.classList.add('s-' + this.value);
        });
    });

    // ── SORT BUTTONS (visual toggle only – form submit handles real sort) ──
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.classList.toggle('active');
        });
    });

});
</script>
@endpush
