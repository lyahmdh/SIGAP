@extends('layouts.app')

@section('title', 'Profil Saya')

@push('styles')
<style>
/* ─── EMAIL VERIFICATION BANNER ─── */
.email-verify-banner {
    position: sticky;
    top: 64px;               /* tepat di bawah navbar */
    z-index: 1040;
    width: 100%;
    background: #FFF8E1;
    border-bottom: 1.5px solid #FFD54F;
    color: #7B5800;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: .9rem 1.5rem;
    overflow: hidden;
    /* initial state — rendered visible, JS akan sembunyikan jika sudah di-dismiss */
    max-height: 200px;
    opacity: 1;
    transition: max-height .35s cubic-bezier(.4,0,.2,1),
                opacity    .25s ease,
                padding    .35s ease;
}
.email-verify-banner.collapsing {
    max-height: 0 !important;
    opacity: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    border-bottom-width: 0 !important;
}
.email-verify-banner .banner-icon {
    font-size: 1.25rem;
    color: #E65100;
    flex-shrink: 0;
    margin-top: 2px;
}
.email-verify-banner .banner-body { flex: 1; font-size: .875rem; line-height: 1.55; }
.email-verify-banner .banner-title {
    font-weight: 700;
    font-size: .9rem;
    margin-bottom: .25rem;
    color: #5D3A00;
}
.email-verify-banner .btn-send-verify {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    margin-top: .5rem;
    padding: .35rem .9rem;
    font-size: .8rem;
    font-weight: 700;
    background: #FFB300;
    border: none;
    border-radius: 6px;
    color: #3E2000;
    cursor: pointer;
    transition: background .18s;
    font-family: inherit;
}
.email-verify-banner .btn-send-verify:hover { background: #FFA000; }
.email-verify-banner .btn-close-banner {
    background: none;
    border: none;
    color: #A07800;
    font-size: 1.1rem;
    cursor: pointer;
    padding: .2rem .4rem;
    border-radius: 4px;
    line-height: 1;
    flex-shrink: 0;
    transition: background .15s;
}
.email-verify-banner .btn-close-banner:hover { background: rgba(0,0,0,.06); }

/* ─── PROFILE LAYOUT ──────────────────────────────── */
.profile-wrapper {
    display: flex;
    min-height: calc(100vh - 64px);
    background: var(--cream);
}

/* ── Sidebar kiri ── */
.profile-sidebar {
    width: 280px;
    flex-shrink: 0;
    background: linear-gradient(170deg, var(--green-light) 0%, var(--green-dark) 100%);
    padding: 3rem 2rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: sticky;
    top: 64px;
    height: calc(100vh - 64px);
    overflow-y: auto;
}

/* Avatar */
.profile-avatar-wrap {
    position: relative;
    margin-bottom: 1.5rem;
}
.profile-avatar {
    width: 160px; height: 160px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.30);
    display: block;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
}
.profile-avatar-initials {
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.20);
    border: 4px solid rgba(255,255,255,.30);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display);
    font-size: 3.5rem;
    font-weight: 600;
    color: var(--white);
    box-shadow: 0 8px 32px rgba(0,0,0,.20);
}

/* User info */
.profile-name {
    font-family: var(--font-display);
    font-size: 1.4rem;
    font-weight: 600;
    color: var(--white);
    text-align: center;
    margin-bottom: .75rem;
}
.profile-info-row {
    display: flex;
    align-items: center;
    gap: .5rem;
    color: rgba(255,255,255,.75);
    font-size: .85rem;
    margin-bottom: .4rem;
    width: 100%;
}
.profile-info-row i {
    width: 16px;
    color: rgba(255,255,255,.55);
    flex-shrink: 0;
    font-size: .9rem;
}

/* Edit button */
.btn-edit-profile {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    margin-top: 1.5rem;
    width: 100%;
    padding: .75rem 1.25rem;
    background: transparent;
    border: 2px solid rgba(255,255,255,.50);
    border-radius: var(--radius-sm);
    color: var(--white);
    font-weight: 700;
    font-size: .9rem;
    font-family: var(--font-main);
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
}
.btn-edit-profile:hover {
    background: rgba(255,255,255,.15);
    border-color: rgba(255,255,255,.80);
    color: var(--white);
}

/* Summary pills */
.summary-pills {
    display: flex;
    flex-direction: column;
    gap: .5rem;
    width: 100%;
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,.15);
}
.summary-pill {
    background: rgba(255,255,255,.10);
    border-radius: var(--radius-sm);
    padding: .55rem .85rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .82rem;
    color: rgba(255,255,255,.80);
}
.summary-pill .pill-count {
    font-weight: 700;
    color: var(--white);
    font-size: .9rem;
}

/* ── Main content ── */
.profile-main {
    flex: 1;
    padding: 2.5rem 2.5rem 3rem;
    min-width: 0;
    overflow-x: hidden;
}

/* ─── RIWAYAT LAPORAN ─────────────────────────────── */
.riwayat-title {
    font-family: var(--font-display);
    font-size: 1.6rem;
    color: var(--green-dark);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

/* Filter bar */
.riwayat-filter {
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.filter-chip {
    border: 1.5px solid var(--cream-dark);
    border-radius: 50px;
    padding: .35rem .9rem;
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    background: var(--white);
    transition: all .18s;
    text-decoration: none;
}
.filter-chip:hover {
    border-color: var(--green-mid);
    color: var(--green-mid);
}
.filter-chip.active {
    background: var(--green-dark);
    border-color: var(--green-dark);
    color: var(--white);
}

/* Riwayat table */
.riwayat-table-wrap {
    background: var(--white);
    border-radius: var(--radius-md);
    border: 1px solid var(--cream-dark);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.riwayat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: .9rem;
}
.riwayat-table thead th {
    background: var(--green-dark);
    color: var(--white);
    padding: .9rem 1.1rem;
    font-weight: 700;
    font-size: .8rem;
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
    text-align: left;
}
.riwayat-table thead th:last-child { text-align: center; }

.sort-icon {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    cursor: pointer;
}
.sort-icon i { font-size: .75rem; opacity: .75; }

.riwayat-table tbody tr {
    border-bottom: 1px solid var(--cream-dark);
    transition: background .15s;
}
.riwayat-table tbody tr:last-child { border-bottom: none; }
.riwayat-table tbody tr:hover { background: var(--cream); }
.riwayat-table td {
    padding: .9rem 1.1rem;
    vertical-align: middle;
    color: var(--text-dark);
}
.riwayat-table td:last-child { text-align: center; }

.td-no { color: var(--text-muted); font-weight: 600; font-size: .85rem; }
.td-title { font-weight: 600; color: var(--green-dark); }
.td-date { color: var(--text-muted); font-size: .85rem; white-space: nowrap; }

/* Status badge */
.badge-status { padding: .3em .8em; border-radius: 50px; font-size: .78rem; font-weight: 700; white-space: nowrap; }
.badge-masuk          { background:#FEF3C7; color:#92400E; }
.badge-diverifikasi   { background:#EDE9FE; color:#5B21B6; }
.badge-ditindaklanjuti{ background:#DBEAFE; color:#1E40AF; }
.badge-selesai        { background:#D1FAE5; color:#065F46; }
.badge-ditolak        { background:#FEE2E2; color:#991B1B; }

/* Detail button */
.btn-detail-riwayat {
    background: var(--cream);
    border: 1.5px solid var(--cream-dark);
    border-radius: var(--radius-sm);
    padding: .35rem .9rem;
    font-size: .8rem;
    font-weight: 700;
    color: var(--green-dark);
    text-decoration: none;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    white-space: nowrap;
}
.btn-detail-riwayat:hover {
    background: var(--green-dark);
    border-color: var(--green-dark);
    color: var(--white);
}

/* Delete button (only status=masuk) */
.btn-hapus-riwayat {
    background: none;
    border: none;
    color: #FC8181;
    font-size: .8rem;
    cursor: pointer;
    padding: .3rem .5rem;
    border-radius: var(--radius-sm);
    transition: all .2s;
    font-family: var(--font-main);
}
.btn-hapus-riwayat:hover { background: #FFF5F5; color: #C53030; }

/* Empty state */
.riwayat-empty {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}
.riwayat-empty i { font-size: 3rem; opacity: .3; display: block; margin-bottom: 1rem; }
.riwayat-empty h5 { font-weight: 700; color: var(--green-dark); margin-bottom: .5rem; }

/* Pagination */
.riwayat-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--cream-dark);
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .8rem;
    color: var(--text-muted);
    flex-wrap: wrap;
    gap: .5rem;
}

/* ─── RESPONSIVE ──────────────────────────────────── */
@media (max-width: 991px) {
    .profile-sidebar {
        width: 100%;
        height: auto;
        position: static;
        padding: 2rem 1.5rem;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 1.25rem;
    }
    .profile-avatar-wrap { margin-bottom: 0; }
    .profile-avatar, .profile-avatar-initials { width: 90px; height: 90px; font-size: 2rem; }
    .profile-info-block { flex: 1; }
    .profile-wrapper { flex-direction: column; }
    .profile-main { padding: 1.5rem; }
    .summary-pills { flex-direction: row; flex-wrap: wrap; }
    .summary-pill { flex: 1; min-width: 120px; }
    .btn-edit-profile { width: auto; padding: .55rem 1.25rem; margin-top: 0; }
}
@media (max-width: 576px) {
    .profile-main { padding: 1rem; }
    .riwayat-table { font-size: .8rem; }
}
</style>
@endpush

@section('content')

@php
$user = $user ?? auth()->user();
$reports = $reports ?? $user->reports()->with('category:id,name')->latest()->paginate(10);
$reportSummary = $reportSummary ?? [
    'total'    => $reports->total() ?? 4,
    'selesai'  => 1,
    'diproses' => 2,
    'masuk'    => 1,
];

// Initials
$nameParts = explode(' ', trim($user->name ?? 'U'));
$initials  = strtoupper(substr($nameParts[0], 0, 1));
if (count($nameParts) > 1) $initials .= strtoupper(substr(end($nameParts), 0, 1));
@endphp

@if(!$user->hasVerifiedEmail())
<div class="email-verify-banner" id="emailVerifyBanner">
    <i class="bi bi-exclamation-triangle-fill banner-icon"></i>

    <div class="banner-body">
        <div class="banner-title">Email Belum Diverifikasi</div>
        Verifikasi email Anda untuk menerima notifikasi perubahan status laporan.
        Setiap kali laporan diverifikasi, ditindaklanjuti, selesai, atau ditolak,
        sistem akan mengirimkan pemberitahuan ke email Anda.

        <div>
            <form method="POST" action="{{ route('verification.send') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn-send-verify">
                    <i class="bi bi-envelope-fill"></i>
                    Kirim Email Verifikasi
                </button>
            </form>
        </div>
    </div>

    <button class="btn-close-banner" id="closeBanner"
            title="Tutup" aria-label="Tutup notifikasi">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@php
    $user = $user ?? auth()->user();
@endphp
<div class="profile-wrapper">

    {{-- ── SIDEBAR ────────────────────────────────── --}}
    <aside class="profile-sidebar">

        {{-- Avatar --}}
        <div class="profile-avatar-wrap">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                     class="profile-avatar" alt="{{ $user->name }}">
            @else
                <div class="profile-avatar-initials">{{ $initials }}</div>
            @endif
        </div>

        <div class="profile-info-block w-100 text-center text-lg-center">
            <div class="profile-name">{{ $user->name }}</div>

            @if($user->email)
            <div class="profile-info-row justify-content-center">
                <i class="bi bi-envelope-fill"></i>
                <span>{{ $user->email }}</span>
            </div>
            @endif

            @if($user->phone ?? null)
            <div class="profile-info-row justify-content-center">
                <i class="bi bi-telephone-fill"></i>
                <span>{{ $user->phone }}</span>
            </div>
            @endif

            <div class="profile-info-row justify-content-center">
                <i class="bi bi-calendar3"></i>
                <span>Bergabung {{ $user->created_at->isoFormat('MMMM Y') }}</span>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn-edit-profile">
                <i class="bi bi-pencil-square"></i>
                Edit Profile
            </a>
        </div>

        {{-- Summary --}}
        <div class="summary-pills">
            <div class="summary-pill">
                <span>Total Laporan</span>
                <span class="pill-count">{{ $reportSummary['total'] }}</span>
            </div>
            <div class="summary-pill">
                <span>Selesai</span>
                <span class="pill-count">{{ $reportSummary['selesai'] }}</span>
            </div>
            <div class="summary-pill">
                <span>Diproses</span>
                <span class="pill-count">{{ $reportSummary['diproses'] }}</span>
            </div>
            <div class="summary-pill">
                <span>Menunggu</span>
                <span class="pill-count">{{ $reportSummary['masuk'] }}</span>
            </div>
        </div>

    </aside>

    {{-- ── MAIN ───────────────────────────────────── --}}
    <main class="profile-main">

        {{-- Flash --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-3 mb-3"
             style="font-size:.875rem">
            <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
        </div>
        @endif

        <h1 class="riwayat-title">Riwayat Laporan</h1>

        {{-- Filter chips --}}
        <div class="riwayat-filter">

            <button
                type="button"
                class="filter-chip filter-btn active"
                data-status="">
                Semua
            </button>

            @foreach([
                'masuk'=>'Masuk',
                'diverifikasi'=>'Diverifikasi',
                'ditindaklanjuti'=>'Ditindaklanjuti',
                'selesai'=>'Selesai'
            ] as $val => $lbl)

                <button
                    type="button"
                    class="filter-chip filter-btn"
                    data-status="{{ $val }}">
                    {{ $lbl }}
                </button>

            @endforeach

        </div>

        {{-- Table --}}
        <div class="riwayat-table-wrap">
            @if($reports->isEmpty())
                <div class="riwayat-empty">
                    <i class="bi bi-clipboard2-x"></i>
                    <h5>Belum Ada Laporan</h5>
                    <p>Anda belum pernah membuat laporan.</p>
                    <a href="{{ route('lapor.create') }}"
                       class="btn-edit-profile d-inline-flex"
                       style="margin-top:.75rem; width:auto; padding:.6rem 1.5rem">
                        <i class="bi bi-plus-circle"></i> Buat Laporan
                    </a>
                </div>
            @else
                <table class="riwayat-table">
                    <thead>
                        <tr>
                            <th style="width:52px">No</th>
                            <th>
                                <span class="sort-icon">
                                    Nama Jalan
                                </span>
                            </th>
                            <th>
                                <span class="sort-icon">
                                    Tanggal Laporan
                                </span>
                            </th>
                            <th>
                                <span class="sort-icon">
                                    Status                                    
                                </span>
                            </th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody id="reports-table-body">
                        @foreach($reports as $i => $report)
                        <tr>
                            <td class="td-no">{{ $reports->firstItem() + $i }}</td>
                            <td class="td-title">{{ $report->title }}</td>
                            <td class="td-date">
                                {{ \Carbon\Carbon::parse($report->created_at)->isoFormat('D MMMM Y') }}
                            </td>
                            <td>
                                @php
                                $statusLabel = [
                                    'masuk'           => 'Masuk',
                                    'diverifikasi'    => 'Diverifikasi',
                                    'ditindaklanjuti' => 'Ditindaklanjuti',
                                    'selesai'         => 'Selesai',
                                    'ditolak'         => 'Ditolak',
                                ][$report->status] ?? ucfirst($report->status);
                                @endphp
                                <span class="badge-status badge-{{ $report->status }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <a href="{{ route('laporan.show', $report->id) }}"
                                       class="btn-detail-riwayat">
                                        Detail
                                    </a>
                                    @if($report->status === 'masuk')
                                    <form method="POST"
                                          action="{{ route('laporan.destroy', $report->id) }}"
                                          onsubmit="return confirm('Hapus laporan ini?')"
                                          style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus-riwayat" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination footer --}}
                @if($reports->hasPages())
                <div class="riwayat-footer">
                    <span>
                        Menampilkan
                        <strong>{{ $reports->firstItem() }}–{{ $reports->lastItem() }}</strong>
                        dari <strong>{{ $reports->total() }}</strong> laporan
                    </span>
                    <div class="d-flex gap-1">
                        {{ $reports->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @else
                <div class="riwayat-footer">
                    <span>Menampilkan <strong>{{ $reports->count() }}</strong> laporan</span>
                </div>
                @endif
            @endif
        </div>{{-- /riwayat-table-wrap --}}

    </main>

</div>{{-- /profile-wrapper --}}

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@push('scripts')
<script>
(function () {
    const banner   = document.getElementById('emailVerifyBanner');
    const closeBtn = document.getElementById('closeBanner');
    if (!banner || !closeBtn) return;

    {{-- Key unik per user — tidak tumpuk antar user, reset tiap login baru --}}
    const KEY = 'emailBannerDismissed_{{ auth()->id() }}';

    if (sessionStorage.getItem(KEY) === '1') {
        banner.style.display = 'none';
        return;
    }

    closeBtn.addEventListener('click', function () {
        banner.classList.add('collapsing');
        banner.addEventListener('transitionend', function h() {
            banner.removeEventListener('transitionend', h);
            banner.style.display = 'none';
        });
        sessionStorage.setItem(KEY, '1');
    });
})();

document.querySelectorAll('.filter-btn').forEach(button => {

    button.addEventListener('click', function() {

        const status = this.dataset.status;

        document.querySelectorAll('.filter-btn')
            .forEach(btn => btn.classList.remove('active'));

        this.classList.add('active');

        axios.get("{{ route('laporan.filter') }}", {
            params: {
                status: status
            }
        })
        .then(response => {

            const reports = response.data;

            let html = '';

            reports.forEach((report, index) => {

                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${report.title}</td>
                        <td>${new Date(report.created_at)
                                .toLocaleDateString('id-ID')}</td>
                        <td>
                            <span class="badge-status badge-${report.status}">
                                ${report.status}
                            </span>
                        </td>
                        <td>
                            <a href="/laporan/${report.id}"
                               class="btn-detail-riwayat">
                                Detail
                            </a>
                        </td>
                    </tr>
                `;
            });

            if(reports.length === 0){
                html = `
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            Tidak ada laporan.
                        </td>
                    </tr>
                `;
            }

            document.getElementById('reports-table-body').innerHTML = html;
        });
    });

});

</script>
@endpush
@endsection
