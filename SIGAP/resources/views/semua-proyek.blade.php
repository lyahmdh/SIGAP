@extends('layouts.app')

@section('title', 'Semua Proyek')

@push('styles')
<style>
    /* ─── PAGE HEADER ───────────────────────────────────── */
    .page-header {
        background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 70%, var(--brown-dark) 100%);
        padding: 3.5rem 0 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -40px; left: 8%;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(224,123,58,.07);
        pointer-events: none;
    }

    .page-header h1 {
        font-family: var(--font-display);
        color: var(--white);
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 600;
        margin-bottom: .4rem;
    }

    .page-header p {
        color: rgba(255,255,255,.65);
        font-size: .95rem;
        margin: 0;
    }

    /* ─── DISTRICT STATS STRIP ──────────────────────────── */
    .district-strip {
        margin: 2rem 0;
    }

    .district-strip .inner {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: .75rem;
    }

    .district-card{
        width: 100%;
        min-width: unset;

        background: #451900;
        border-radius: 20px;

        padding: 24px 12px;

        text-align: center;
    }

    .district-stat {
        text-align: center;
        flex: 1;
        min-width: 90px;
        border-right: 1px solid rgba(255,255,255,.12);
        padding: .5rem 1rem;
    }
    .district-stat:last-child { border-right: none; }

    .district-stat .num {
        font-family: var(--font-display);
        font-size: 1.9rem;
        font-weight: 600;
        color: var(--white);
        line-height: 1;
    }
    .district-stat .lbl {
        font-size: .75rem;
        color: rgba(255,255,255,.55);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .07em;
        margin-top: .3rem;
    }

    .district-wrapper{
        background: linear-gradient(
            90deg,
            #8f3d00,
            #b45400
        );

        border-radius: 24px;
        padding: 24px;

        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 24px;

        width: 100%;
    }
    
    .district-card:hover {
        transform: translateY(-3px);
    }

    .district-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        color: #fff;
    }

    .district-name {
        margin-top: .75rem;

        font-size: .95rem;
        font-weight: 600;

        color: #fff;
    }
    /* ─── FILTER BAR ─────────────────────────────────────── */
    .filter-bar {
        background: var(--white);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--cream-dark);
        margin-bottom: 2rem;
    }

    .filter-bar .form-select,
    .filter-bar .form-control {
        border: 1.5px solid var(--cream-dark);
        border-radius: var(--radius-sm);
        font-size: .875rem;
        padding: .55rem .9rem;
        color: var(--text-dark);
        background-color: var(--cream);
        transition: border-color .2s;
    }
    .filter-bar .form-select:focus,
    .filter-bar .form-control:focus {
        border-color: var(--green-light);
        box-shadow: 0 0 0 3px rgba(74,124,78,.12);
        background-color: var(--white);
    }

    .btn-search {
        background: var(--green-mid);
        color: var(--white);
        border: none;
        border-radius: var(--radius-sm);
        padding: .55rem 1.25rem;
        font-weight: 600;
        font-size: .875rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: background .2s;
        white-space: nowrap;
    }
    .btn-search:hover { background: var(--green-dark); color: var(--white); }

    .btn-sort {
        background: var(--cream);
        border: 1.5px solid var(--cream-dark);
        color: var(--text-muted);
        border-radius: var(--radius-sm);
        padding: .5rem .75rem;
        font-size: .875rem;
        transition: all .2s;
        cursor: pointer;
    }
    .btn-sort:hover { border-color: var(--green-light); color: var(--green-mid); }
    .btn-sort.active { border-color: var(--green-mid); color: var(--green-mid); background: rgba(74,124,78,.07); }

    /* ─── RESULTS INFO ───────────────────────────────────── */
    .results-info {
        font-size: .875rem;
        color: var(--text-muted);
        margin-bottom: 1.25rem;
    }
    .results-info strong { color: var(--text-dark); }

    /* ─── PROJECT CARD ───────────────────────────────────── */
    .project-card {
        background: var(--white);
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--cream-dark);
        display: flex;
        gap: 0;
        height: 100%;
        flex-direction: column;
        transition: all .25s;
    }
    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: rgba(74,124,78,.25);
    }

    .project-card-img {
        position: relative;
        flex-shrink: 0;
        height: 170px;
        overflow: hidden;
    }
    .project-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .project-card:hover .project-card-img img { transform: scale(1.05); }

    .project-card-img .status-badge {
        position: absolute;
        top: .75rem;
        left: .75rem;
    }

    .project-card-body {
        padding: 1.1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .project-card-category {
        font-size: .75rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: .4rem;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .project-card-title {
        font-weight: 700;
        font-size: .975rem;
        color: var(--green-dark);
        margin-bottom: .5rem;
        line-height: 1.35;
        flex: 1;
        /* clamp to 2 lines */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .project-card-meta {
        font-size: .775rem;
        color: var(--text-muted);
        margin-bottom: .9rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .project-card-meta .meta-item {
        display: flex;
        align-items: center;
        gap: .25rem;
    }

    .btn-detail {
        display: block;
        text-align: center;
        background: var(--green-mid);
        color: var(--white);
        border: none;
        border-radius: var(--radius-sm);
        padding: .55rem;
        font-weight: 600;
        font-size: .875rem;
        text-decoration: none;
        transition: background .2s;
        margin-top: auto;
    }
    .btn-detail:hover { background: var(--green-dark); color: var(--white); }

    /* Priority score pill */
    .priority-pill {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .72rem;
        font-weight: 700;
        padding: .25em .65em;
        border-radius: 50px;
        background: rgba(224,123,58,.12);
        color: var(--brown-accent);
    }

    /* ─── EMPTY STATE ────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 3.5rem; opacity: .35; margin-bottom: 1rem; }
    .empty-state h5 { font-weight: 700; color: var(--green-dark); margin-bottom: .5rem; }

    /* ─── PAGINATION ─────────────────────────────────────── */
    .pagination .page-link {
        border-radius: var(--radius-sm) !important;
        border-color: var(--cream-dark);
        color: var(--green-mid);
        font-weight: 500;
        font-size: .875rem;
        margin: 0 .15rem;
        transition: all .2s;
    }
    .pagination .page-link:hover {
        background: var(--green-pale);
        border-color: var(--green-light);
    }
    .pagination .page-item.active .page-link {
        background: var(--green-mid);
        border-color: var(--green-mid);
        color: var(--white);
    }
    .pagination .page-item.disabled .page-link { opacity: .4; }
</style>
@endpush

@section('content')

{{-- ─────────────────────────────────────────────────────────────
     PAGE HEADER
────────────────────────────────────────────────────────────── --}}
<div class="page-header">
    <div class="container">
        <h1>Semua Laporan</h1>
        <p>Daftar seluruh laporan fasilitas publik yang dapat diakses masyarakat.</p>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────
     DISTRICT STATS
────────────────────────────────────────────────────────────── --}}
<div class="district-strip">
    <div class="container">
        <div class="district-wrapper">
            @foreach($districtStats as $ds)
                <div class="district-card">
                    <div class="district-number">
                        {{ $ds->count }}
                    </div>
                    <div class="district-name">
                        {{ $ds->name }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ─────────────────────────────────────────────────────────────
     MAIN CONTENT
────────────────────────────────────────────────────────────── --}}
<div style="padding: 2.5rem 0 4rem; background: var(--cream);">
    <div class="container">

        {{-- ── FILTER BAR ──────────────────────────────── --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('rekap') ?? '/rekap' }}" id="filterForm">
                <div class="row g-2 align-items-center">

                    {{-- Kecamatan --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <select name="kecamatan" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Kecamatan</option>
                            @foreach($districtStats as $ds)
                                <option value="{{ $ds['name'] }}"
                                    {{ (request('kecamatan') == $ds['name']) ? 'selected' : '' }}>
                                    {{ $ds['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kategori --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <select name="kategori" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->name }}"
                                {{ request('kategori') == $category->name ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-sm-6 col-lg-2">
                        <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Status</option>
                            <option value="masuk"          {{ request('status')=='masuk'          ? 'selected':'' }}>Laporan Masuk</option>
                            <option value="diverifikasi"   {{ request('status')=='diverifikasi'   ? 'selected':'' }}>Diverifikasi</option>
                            <option value="ditindaklanjuti" {{ request('status')=='ditindaklanjuti' ? 'selected':'' }}>Ditindaklanjuti</option>
                            <option value="selesai"        {{ request('status')=='selesai'        ? 'selected':'' }}>Selesai</option>
                            <option value="ditolak"        {{ request('status')=='ditolak'        ? 'selected':'' }}>Ditolak</option>
                        </select>
                    </div>

                    {{-- Search text --}}
                    <div class="col-12 col-sm-6 col-lg-3">
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               class="form-control"
                               placeholder="Cari judul laporan…">
                    </div>

                    {{-- Sort --}}
                    <div class="col-auto">
                        <button type="button"
                                class="btn-sort {{ request('sort','terbaru')==='priority' ? 'active':'' }}"
                                id="sortToggle"
                                title="Urutkan">
                            <i class="bi bi-arrow-down-up"></i>
                        </button>
                        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort','terbaru') }}">
                    </div>

                    {{-- Search button --}}
                    <div class="col-auto ms-auto">
                        <button type="submit" class="btn-search">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>
                    </div>

                    {{-- Reset --}}
                    @if(request()->hasAny(['kecamatan','kategori','status','q','sort']))
                    <div class="col-auto">
                        <a href="{{ route('rekap') ?? '/rekap' }}"
                           class="text-decoration-none"
                           style="font-size:.8rem; color:var(--text-muted);">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                    @endif

                </div>
            </form>
        </div>

        {{-- ── RESULTS + CARDS ─────────────────────────── --}}

        <div class="d-flex justify-content-between align-items-center results-info mb-3">
            <span>Menampilkan <strong>{{ $reports->total() }}</strong> laporan</span>
            <span style="font-size:.775rem; color:var(--text-muted);">
                Diurutkan:
                <strong style="color:var(--green-mid);">
                    {{ request('sort','terbaru') === 'priority' ? 'Priority Score' : 'Terbaru' }}
                </strong>
            </span>
        </div>

        @if($reports->isEmpty())
            <div class="empty-state">
                <div><i class="bi bi-folder2-open"></i></div>
                <h5>Tidak Ada Laporan Ditemukan</h5>
                <p>Coba ubah filter pencarian Anda.</p>
            </div>
        @else
            <div class="row g-3">
                @foreach($reports as $report)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="project-card">

                        {{-- Image --}}


                        <div class="project-card-img">
                        @php
                            $coverImage = $report->images->first();
                        @endphp
                        <img src="{{ $coverImage
                                ? asset('storage/' . $coverImage->image_path)
                                : asset('images/placeholder.jpg') }}"
                            alt="{{ $report->title }}"
                            loading="lazy">
                    

                            {{-- Status badge --}}
                            <span class="status-badge">
                                @php
                                $statusMap = [
                                    'masuk'        => ['class' => 'badge-masuk',        'label' => 'Laporan Masuk'],
                                    'diverifikasi' => ['class' => 'badge-diverifikasi',  'label' => 'Diverifikasi'],
                                    'diproses'     => ['class' => 'badge-diproses',      'label' => 'Diproses'],
                                    'selesai'      => ['class' => 'badge-selesai',       'label' => 'Selesai'],
                                    'ditolak'      => ['class' => 'badge-ditolak',       'label' => 'Ditolak'],
                                ];
                                $s = $statusMap[$report->status] ?? ['class'=>'badge-masuk','label'=>ucfirst($report->status)];
                                @endphp
                                <span class="badge-status {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="project-card-body">
                            <div class="project-card-category">
                                <i class="bi bi-tag"></i>
                                {{ $report->category->name }}
                            </div>

                            <div class="project-card-title">{{ $report->title }}</div>

                            <div class="project-card-meta">
                                <span class="meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $report->district }}
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-calendar3"></i>
                                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Priority score --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="priority-pill">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    Prioritas {{ number_format($report->priority_score, 0) }}
                                </span>
                            </div>

                            <a href="{{ route('laporan.show', $report->id) ?? '/laporan/'.$report->id }}"
                               class="btn-detail">
                                Lihat Detail
                            </a>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>

            {{-- ── PAGINATION ──────────────────────────── --}}
            @if(isset($reports) && method_exists($reports, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $reports->withQueryString()->links() }}
            </div>
            @else
            {{-- Demo pagination --}}
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="pagination">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
            @endif
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    // Sort toggle: terbaru ↔ priority
    const sortToggle = document.getElementById('sortToggle');
    const sortInput  = document.getElementById('sortInput');

    if (sortToggle && sortInput) {
        sortToggle.addEventListener('click', () => {
            const current = sortInput.value;
            sortInput.value = current === 'priority' ? 'terbaru' : 'priority';
            sortToggle.classList.toggle('active');
            // Show tooltip
            sortToggle.title = sortInput.value === 'priority'
                ? 'Urutan: Priority Score (klik untuk terbaru)'
                : 'Urutan: Terbaru (klik untuk priority)';
            document.getElementById('filterForm').submit();
        });
    }
</script>
@endpush
