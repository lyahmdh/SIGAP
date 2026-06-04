@extends('layouts.app')

@section('title', $report->title)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ─── HERO BANNER ─────────────────────────────────── */
.detail-hero {
    position: relative;
    height: 320px;
    overflow: hidden;
    background: var(--green-dark);
}
.detail-hero-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: .45;
    filter: saturate(.65);
}
.detail-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom,
        rgba(30,58,31,.3) 0%,
        rgba(30,58,31,.85) 100%);
}
.detail-hero-content {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 0;
}

/* ─── INFO CARD (overlapping hero) ───────────────── */
.info-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    /* box-shadow: var(--shadow-lg); */
    padding: 2rem;
    margin-top: -80px;
    position: relative;
    z-index: 10;
    border: 1px solid var(--cream-dark);
}

.report-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 3vw, 2rem);
    color: var(--green-dark);
    font-weight: 600;
    line-height: 1.25;
    margin-bottom: .5rem;
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
}
.report-meta-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .6rem;
    margin-bottom: 1rem;
}
.report-author {
    font-size: .875rem;
    color: var(--brown-accent);
    font-style: italic;
    font-weight: 500;
}
.report-location {
    font-size: .875rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: .3rem;
}
.report-category-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    background: var(--green-dark);
    color: var(--white);
    font-size: .775rem;
    font-weight: 700;
    padding: .35em .85em;
    border-radius: 50px;
}

/* Photo gallery grid */
/* Photo gallery grid */
.photo-grid {
    border-radius: var(--radius-md);
    overflow: hidden;
}

/* Single foto — rounded semua sisi */
.photo-grid.single {
    height: 220px;
}
.photo-grid.single .photo-grid-item {
    height: 100%;
    border-radius: var(--radius-md);
    overflow: hidden;
}

/* 2–3 foto — grid layout */
.photo-grid.multi {
    display: grid;
    grid-template-columns: 2fr 1fr;
    grid-template-rows: repeat(2, 110px);
    gap: .5rem;
}
.photo-grid.multi .photo-grid-item:first-child {
    grid-row: 1 / 3;
}

.photo-grid-item {
    overflow: hidden;
    cursor: pointer;
    position: relative;
    border-radius: 0;
}
.photo-grid-item img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .3s;
    display: block;
}
.photo-grid-item:hover img { transform: scale(1.04); }

/* +N overlay pada foto terakhir */
.photo-grid-more::after {
    content: attr(data-more);
    position: absolute;
    inset: 0;
    background: rgba(30,58,31,.65);
    color: var(--white);
    font-size: 1.4rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ─── STATUS TRACKER ──────────────────────────────── */
.status-tracker {
    background: var(--white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--cream-dark);
    padding: 2rem;
    margin-bottom: 1.5rem;
}

.tracker-steps {
    display: flex;
    align-items: flex-start;
    position: relative;
    gap: 0;
}
.tracker-steps::before {
    content: '';
    position: absolute;
    top: 28px;
    left: calc(12.5%);
    right: calc(12.5%);
    height: 2px;
    background: var(--cream-dark);
    z-index: 0;
}

.tracker-step {
    flex: 1;
    text-align: center;
    position: relative;
    z-index: 1;
}
.tracker-circle {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--cream-dark);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .65rem;
    font-size: 1.3rem;
    border: 3px solid var(--cream-dark);
    transition: all .3s;
    position: relative;
    z-index: 1;
}
.tracker-step.done .tracker-circle {
    background: var(--green-pale);
    border-color: var(--green-mid);
    color: var(--green-mid);
}
.tracker-step.current .tracker-circle {
    background: var(--green-dark);
    border-color: var(--green-dark);
    color: var(--white);
    box-shadow: 0 4px 16px rgba(30,58,31,.30);
}
.tracker-label {
    font-size: .8rem;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: .2rem;
}
.tracker-step.done .tracker-label,
.tracker-step.current .tracker-label { color: var(--green-dark); }
.tracker-date {
    font-size: .72rem;
    color: var(--text-muted);
}

/* ─── CONTENT SECTIONS ────────────────────────────── */
.section-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    border: 1px solid var(--cream-dark);
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}
.section-card-title {
    font-family: var(--font-display);
    font-size: 1.15rem;
    color: var(--green-dark);
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: .75rem;
    border-bottom: 2px solid var(--cream-dark);
    display: flex;
    align-items: center;
    gap: .5rem;
}
.section-card-title i { color: var(--brown-accent); }

.desc-text {
    font-size: .935rem;
    color: var(--text-muted);
    line-height: 1.8;

        overflow-wrap: anywhere;
    word-break: break-word;
}

/* Map in detail */
#detail-map {
    height: 220px;
    border-radius: var(--radius-md);
    border: 1.5px solid var(--cream-dark);
    margin-top: .75rem;
}

.leaflet-popup-content {
    max-width: 220px;
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* ─── PRIORITY PILL ───────────────────────────────── */
.priority-card {
    background: linear-gradient(135deg, var(--green-pale), rgba(74,124,78,.08));
    border-radius: var(--radius-md);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}
.priority-score-circle {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--green-dark);
    color: var(--white);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    flex-shrink: 0;
}
.priority-info .prio-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--green-mid);
    margin-bottom: .1rem;
}
.priority-info .prio-val {
    font-size: .875rem;
    color: var(--text-muted);
}

/* ─── PROJECT UPDATES ─────────────────────────────── */
.update-timeline { position: relative; }
.update-timeline::before {
    content: '';
    position: absolute;
    left: 18px; top: 0; bottom: 0;
    width: 2px;
    background: var(--cream-dark);
}
.update-item {
    display: flex;
    gap: 1rem;
    padding-bottom: 1.5rem;
    position: relative;
}
.update-item:last-child { padding-bottom: 0; }
.update-dot {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: var(--green-pale);
    border: 2px solid var(--green-mid);
    display: flex; align-items: center; justify-content: center;
    color: var(--green-mid);
    font-size: .9rem;
    flex-shrink: 0;
    z-index: 1;
}
.update-content { flex: 1; }
.update-date {
    font-size: .775rem;
    font-weight: 700;
    color: var(--brown-accent);
    margin-bottom: .2rem;
}
.update-text {
    font-size: .875rem;
    color: var(--text-muted);
    line-height: 1.7;
}
.update-photos {
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
    margin-top: .6rem;
}
.update-photo-thumb {
    width: 64px; height: 64px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    border: 1.5px solid var(--cream-dark);
    cursor: pointer;
}
.update-photo-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s; }
.update-photo-thumb:hover img { transform: scale(1.08); }

/* ─── COMMENTS ────────────────────────────────────── */
.comment-item {
    display: flex;
    gap: .85rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--cream-dark);
}
.comment-item:last-of-type { border-bottom: none; }
.comment-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--green-pale);
    border: 1.5px solid var(--green-mid);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    font-weight: 700;
    color: var(--green-dark);
    flex-shrink: 0;
    overflow: hidden;
}
.comment-avatar img { width: 100%; height: 100%; object-fit: cover; }
.comment-name {
    font-weight: 700;
    font-size: .875rem;
    color: var(--green-dark);
    margin-bottom: .2rem;
}
.comment-name.anon { color: var(--text-muted); font-style: italic; }
.comment-text {
    font-size: .875rem;
    color: var(--text-muted);
    line-height: 1.65;
}
.comment-date {
    font-size: .72rem;
    color: #A0AEC0;
    margin-top: .25rem;
}
.comment-delete-btn {
    background: none;
    border: none;
    color: #FC8181;
    font-size: .75rem;
    cursor: pointer;
    padding: 0;
    margin-left: auto;
    align-self: flex-start;
    flex-shrink: 0;
}

/* Comment form */
.comment-form-wrap {
    margin-top: 1rem;
    display: flex;
    gap: .65rem;
    align-items: flex-end;
}
.comment-input {
    flex: 1;
    border: 1.5px solid var(--cream-dark);
    border-radius: var(--radius-sm);
    padding: .65rem 1rem;
    font-size: .875rem;
    font-family: var(--font-main);
    background: var(--cream);
    transition: border-color .2s;
    resize: none;
    min-height: 44px;
}
.comment-input:focus {
    outline: none;
    border-color: var(--green-light);
    background: var(--white);
}
.btn-kirim-komentar {
    background: var(--green-mid);
    color: var(--white);
    border: none;
    border-radius: var(--radius-sm);
    padding: .65rem 1.25rem;
    font-weight: 700;
    font-size: .875rem;
    cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
}
.btn-kirim-komentar:hover { background: var(--green-dark); }

/* ─── STATUS BADGE ────────────────────────────────── */
.badge-status { padding: .35em .75em; border-radius: 50px; font-size: .75rem; font-weight: 600; }
.badge-masuk        { background:#FEF3C7; color:#92400E; }
.badge-diverifikasi { background:#EDE9FE; color:#5B21B6; }
.badge-ditindaklanjuti { background:#DBEAFE; color:#1E40AF; }
.badge-selesai      { background:#D1FAE5; color:#065F46; }
.badge-ditolak      { background:#FEE2E2; color:#991B1B; }

/* ─── LIGHTBOX ─────────────────────────────────────── */
.lightbox-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.88);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.lightbox-overlay.show { display: flex; }
.lightbox-overlay img {
    max-width: 90vw;
    max-height: 90vh;
    border-radius: var(--radius-md);
    object-fit: contain;
}
.lightbox-close {
    position: absolute;
    top: 1rem; right: 1.25rem;
    color: var(--white);
    font-size: 2rem;
    cursor: pointer;
    background: none;
    border: none;
    line-height: 1;
}

@media (max-width: 768px) {
    .info-card { margin-top: -40px; }
    .photo-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
    .photo-grid-item:first-child { grid-row: auto; grid-column: auto; }
    .tracker-label { font-size: .7rem; }
    .tracker-circle { width: 44px; height: 44px; font-size: 1rem; }
    .tracker-steps::before { top: 22px; }
}
</style>
@endpush

@section('content')

{{-- LIGHTBOX --}}
<div class="lightbox-overlay" id="lightbox">
    <button class="lightbox-close" id="lightboxClose">&times;</button>

    {{-- Navigasi --}}
    <button id="lbPrev" style="
        position:absolute; left:1.25rem; top:50%; transform:translateY(-50%);
        background:rgba(255,255,255,.15); border:none; border-radius:50%;
        width:48px; height:48px; display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:1.4rem; cursor:pointer; backdrop-filter:blur(4px);
        transition:background .2s;">&#8249;</button>

    <img src="" id="lightboxImg" alt=""
         style="max-width:90vw; max-height:82vh; border-radius:var(--radius-md); object-fit:contain; user-select:none;">

    <button id="lbNext" style="
        position:absolute; right:1.25rem; top:50%; transform:translateY(-50%);
        background:rgba(255,255,255,.15); border:none; border-radius:50%;
        width:48px; height:48px; display:flex; align-items:center; justify-content:center;
        color:#fff; font-size:1.4rem; cursor:pointer; backdrop-filter:blur(4px);
        transition:background .2s;">&#8250;</button>

    <div id="lbCounter" style="
        position:absolute; bottom:1.25rem; left:50%; transform:translateX(-50%);
        color:rgba(255,255,255,.75); font-size:.85rem; font-weight:600;
        background:rgba(0,0,0,.35); padding:.3rem .9rem; border-radius:50px;
        backdrop-filter:blur(4px);">
    </div>
</div>

{{-- ── HERO ──────────────────────────────────────── --}}
<div class="detail-hero">
    @if($report->images->isNotEmpty())
        <img src="{{ asset('storage/' . $report->images->first()->image_path) }}"
             class="detail-hero-bg" alt="{{ $report->title }}">
    @else
        <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&q=70"
             class="detail-hero-bg" alt="">
    @endif
    <div class="detail-hero-overlay"></div>
</div>

{{-- ── MAIN CONTENT ──────────────────────────────── --}}
<div style="background:var(--cream); padding-bottom:3rem;">
    <div class="container">

        {{-- Session alerts --}}
        @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mt-3 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif

        <div class="row g-4 align-items-start">

            {{-- INFO CARD (overlaps hero) --}}
            <div class="info-card">
                <div class="row align-items-start g-3">

                    {{-- Title & meta --}}
                    <div class="col-md-7">
                        <h1 class="report-title">{{ $report->title }}</h1>
                        <div class="report-meta-row">
                            <span class="report-author">
                                oleh {{ $report->is_anonymous ? 'Anonim' : ($report->user->name ?? 'Pengguna') }}
                            </span>
                        </div>
                        <div class="report-location mb-2">
                            <i class="bi bi-geo-alt-fill" style="color:var(--brown-accent)"></i>
                            {{ $report->formatted_address ?? $report->district }}
                        </div>
                        <span class="report-category-badge">
                            <i class="bi bi-tag-fill"></i>
                            {{ $report->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    {{-- Photo gallery --}}
                    <div class="col-md-5">
                        @if($report->images->isNotEmpty())
                        @php
                            $imgs      = $report->images;
                            $total     = $imgs->count();
                            $displayed = $imgs->take(3);
                            $extra     = $total > 3 ? $total - 3 : 0;
                            $isSingle  = $total === 1;
                        @endphp
                        <div class="photo-grid {{ $isSingle ? 'single' : 'multi' }}">
                            @foreach($displayed as $i => $img)
                                @php
                                    $src     = asset('storage/' . $img->image_path);
                                    $isLast  = $i === $displayed->count() - 1;
                                    $showMore = $isLast && $extra > 0;
                                @endphp
                                <div class="photo-grid-item {{ $showMore ? 'photo-grid-more' : '' }}"
                                    {{ $showMore ? 'data-more=+' . $extra : '' }}
                                    data-index="{{ $i }}"
                                    onclick="openLightbox({{ $i }})">
                                    <img src="{{ $src }}" alt="Foto laporan {{ $i+1 }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>

                        {{-- Semua foto untuk lightbox slider (hidden) --}}
                        <div id="lightbox-sources" style="display:none">
                            @foreach($imgs as $img)
                                <span data-src="{{ asset('storage/' . $img->image_path) }}"></span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- STATUS TRACKER --}}
            <div class="status-tracker">
                @php
                $statusOrder  = ['masuk', 'diverifikasi', 'ditindaklanjuti', 'selesai'];
                $currentIdx   = array_search($report->status, $statusOrder);
                $currentIdx   = $currentIdx === false ? 0 : $currentIdx;

                $trackerSteps = [
                    ['icon' => 'bi-inbox-fill',      'label' => 'Laporan Masuk',      'status_key' => 'masuk'],
                    ['icon' => 'bi-book-fill',       'label' => 'Proses Verifikasi',  'status_key' => 'diverifikasi'],
                    ['icon' => 'bi-fire',            'label' => 'Proses Tindak Lanjut','status_key' => 'ditindaklanjuti'],
                    ['icon' => 'bi-check-circle-fill','label' => 'Selesai',           'status_key' => 'selesai'],
                ];
                @endphp

                <div class="tracker-steps">
                    @foreach($trackerSteps as $idx => $step)
                    @php
                        $isDone    = $idx < $currentIdx;
                        $isCurrent = $idx === $currentIdx;
                    @endphp
                    <div class="tracker-step {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
                        <div class="tracker-circle">
                            <i class="bi {{ $step['icon'] }}"></i>
                        </div>
                        <div class="tracker-label">{{ $step['label'] }}</div>
                        {{-- Tanggal: hanya tampil jika ada data --}}
                        <div class="tracker-date">
                            @if($idx === 0)
                                {{ $report->created_at->format('d M Y') }}
                            @elseif($isCurrent || $isDone)
                                {{ $report->updated_at->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Status badge --}}
                <div class="mt-3 pt-3" style="border-top:1px solid var(--cream-dark)">
                    <span style="font-size:.8rem; color:var(--text-muted)">Status saat ini:</span>
                    <span class="badge-status badge-{{ $report->status }} ms-2">
                        {{ ucfirst(str_replace('ditindaklanjuti', 'Ditindaklanjuti', $report->status)) }}
                    </span>
                </div>
            </div>

            <div class="row g-2">
                {{-- ── LEFT: DESC + UPDATES ──── --}}
                <div class="col-lg-7">

                    {{-- DESKRIPSI PROYEK --}}
                    <div class="section-card">
                        <div class="section-card-title">
                            <i class="bi bi-file-text-fill"></i>
                            Deskripsi Proyek
                        </div>
                        <p class="desc-text">{{ $report->description }}</p>

                        {{-- Peta lokasi --}}
                        <div id="detail-map"></div>
                        <div style="font-size:.78rem; color:var(--text-muted); margin-top:.5rem; display:flex; align-items:center; gap:.35rem">
                            <i class="bi bi-pin-map-fill" style="color:var(--brown-accent)"></i>
                            {{ $report->formatted_address ?? $report->district }}
                        </div>
                    </div>

                    {{-- PROJECT UPDATES --}}
                    <div class="section-card">
                        <div class="section-card-title">
                            <i class="bi bi-calendar-event-fill"></i>
                            Update Proyek
                        </div>

                        {{-- FORM TAMBAH UPDATE (ADMIN ONLY) --}}
                        @auth
                        @if(auth()->user()->role === 'admin')

                        <form action="{{ route('admin.laporan.update.store', $report->id) }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="mb-4">

                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Judul Update</label>
                                <input type="text"
                                    name="title"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description"
                                        rows="3"
                                        class="form-control"
                                        required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Progress</label>
                                <input type="file"
                                    name="images[]"
                                    multiple
                                    class="form-control">
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i>
                                Tambah Update
                            </button>

                        </form>

                        <hr>

                        @endif
                        @endauth

                        @if($report->projectUpdates->isNotEmpty())
                            <div class="update-timeline">
                                @foreach($report->projectUpdates as $update)
                                <div class="update-item">
                                    <div class="update-dot">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>

                                    <div class="update-content">
                                        <div class="update-date">
                                            {{ \Carbon\Carbon::parse($update->created_at)->isoFormat('D MMMM Y') }}
                                        </div>

                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-700"
                                                style="font-size:.9rem; color:var(--green-dark); margin-bottom:.3rem">
                                                {{ $update->title }}
                                            </div>
                                        </div>

                                        @auth
                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('admin.laporan.update.destroy', $update->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus update proyek ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @endauth
                                    </div>

                                        <div class="update-text">
                                            {{ $update->description }}
                                        </div>

                                        @if($update->images->isNotEmpty())
                                        <div class="update-photos">
                                            @foreach($update->images as $uImg)
                                            @php
                                                $uSrc = asset('storage/' . $uImg->image_path);
                                            @endphp

                                            <div class="update-photo-thumb"
                                                onclick="openLightboxSingle(this)">
                                                <img src="{{ $uSrc }}"
                                                    alt="Update foto"
                                                    loading="lazy">
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x"
                                style="font-size:2rem; color:#A0AEC0;"></i>

                                <div class="mt-2"
                                    style="font-size:.9rem; color:var(--text-muted);">
                                    Belum ada update terbaru dari admin.
                                </div>
                            </div>
                        @endif
                    </div>

                </div>{{-- /col-lg-7 --}}

                {{-- ── RIGHT: COMMENTS ────────────── --}}
                <div class="col-lg-5" style="position: sticky; top: 5.5rem;">

                    {{-- KOMENTAR WARGA --}}
                    <div class="section-card">
                        <div class="section-card-title">
                            <i class="bi bi-chat-dots-fill"></i>
                            Komentar Warga
                        </div>

                        {{-- Daftar komentar --}}
                        @if($report->comments->isNotEmpty())
                            @foreach($report->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    @if($comment->user?->profile_photo)
                                        <img src="{{ asset('storage/' . $comment->user->profile_photo) }}"
                                            alt="{{ $comment->user->name }}">
                                    @else
                                        {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                                    @endif
                                </div>
                                <div class="flex-fill">
                                    <div class="comment-name">{{ $comment->user->name ?? 'Pengguna' }}</div>
                                    <div class="comment-text">{{ $comment->comment }}</div>
                                    <div class="comment-date">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                {{-- Hapus komentar sendiri --}}
                                @auth
                                    @if(auth()->id() === $comment->user_id)
                                    <form method="POST"
                                        action="{{ route('komentar.destroy', $comment->id) }}"
                                        onsubmit="return confirm('Hapus komentar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="comment-delete-btn" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                    @endif
                                @endauth
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-3" style="color:var(--text-muted); font-size:.875rem">
                                <i class="bi bi-chat-square-dots" style="font-size:2rem; opacity:.3; display:block; margin-bottom:.5rem"></i>
                                Belum ada komentar. Jadilah yang pertama!
                            </div>
                        @endif

                        {{-- Form komentar --}}
                        @auth
                            <form method="POST"
                                action="{{ route('komentar.store', $report->id) }}"
                                id="commentForm">
                                @csrf
                                <div class="comment-form-wrap">
                                    <textarea name="comment"
                                            class="comment-input"
                                            placeholder="Tulis komentar…"
                                            rows="1"
                                            maxlength="500"
                                            required>{{ old('comment') }}</textarea>
                                    <button type="submit" class="btn-kirim-komentar">
                                        <i class="bi bi-send-fill me-1"></i>Kirim
                                    </button>
                                </div>
                                @error('comment')
                                <div style="font-size:.775rem; color:#E53E3E; margin-top:.3rem">{{ $message }}</div>
                                @enderror
                            </form>
                        @else
                            <div class="mt-3 p-3 text-center"
                                style="background:var(--cream); border-radius:var(--radius-md); font-size:.85rem; color:var(--text-muted)">
                                <a href="{{ route('login') }}" style="color:var(--green-mid); font-weight:700">Masuk</a>
                                untuk menambahkan komentar.
                            </div>
                        @endauth
                    </div>

                </div>{{-- /col-lg-5 --}}
            </div>
        </div>{{-- /row --}}
    </div>{{-- /container --}}
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── MAP ────────────────────────────────────────────────
    const lat = {{ $report->latitude ?? -7.9666 }};
    const lng = {{ $report->longitude ?? 112.6326 }};

    const map = L.map('detail-map', {
        zoomControl: true,
        scrollWheelZoom: false,
        dragging: true,
    }).setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup(`<b>{{ addslashes($report->title) }}</b><br>{{ addslashes($report->formatted_address ?? '') }}`)
        .openPopup();

    // ── LIGHTBOX DENGAN SLIDE ─────────────────────────
    const overlay = document.getElementById('lightbox');
    const lbImg   = document.getElementById('lightboxImg');
    const lbClose = document.getElementById('lightboxClose');

    // Foto laporan utama (untuk grid di info-card)
    const allSrcs = [...document.querySelectorAll('#lightbox-sources span')]
                        .map(el => el.dataset.src);
    let currentIdx = 0;
    let currentPool = []; // pool aktif saat lightbox terbuka

    function openLightboxPool(srcs, idx) {
        currentPool = srcs;
        currentIdx  = idx;
        lbImg.src   = currentPool[currentIdx];
        updateNav();
        overlay.classList.add('show');
    }

    // Dipanggil dari foto grid laporan (pakai index)
    window.openLightbox = function(idx) {
        openLightboxPool(allSrcs, idx);
    };

    // Dipanggil dari foto project update (pakai src langsung)
    window.openLightboxSingle = function(el) {
        // Kumpulkan semua foto dalam update-item yang sama
        const updateItem = el.closest('.update-item');
        const srcs = [...updateItem.querySelectorAll('.update-photo-thumb img')]
                        .map(img => img.src);
        const clicked = el.querySelector('img').src;
        const idx = srcs.indexOf(clicked);
        openLightboxPool(srcs, idx >= 0 ? idx : 0);
    };

    function updateNav() {
        document.getElementById('lbPrev').style.display =
            currentIdx > 0 ? 'flex' : 'none';
        document.getElementById('lbNext').style.display =
            currentIdx < currentPool.length - 1 ? 'flex' : 'none';
        document.getElementById('lbCounter').textContent =
            currentPool.length > 1 ? `${currentIdx + 1} / ${currentPool.length}` : '';
    }

    document.getElementById('lbPrev').addEventListener('click', e => {
        e.stopPropagation();
        if (currentIdx > 0) { currentIdx--; lbImg.src = currentPool[currentIdx]; updateNav(); }
    });
    document.getElementById('lbNext').addEventListener('click', e => {
        e.stopPropagation();
        if (currentIdx < currentPool.length - 1) { currentIdx++; lbImg.src = currentPool[currentIdx]; updateNav(); }
    });

    lbClose.addEventListener('click', () => overlay.classList.remove('show'));
    overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('show'); });
    document.addEventListener('keydown', e => {
        if (!overlay.classList.contains('show')) return;
        if (e.key === 'Escape')     overlay.classList.remove('show');
        if (e.key === 'ArrowLeft')  document.getElementById('lbPrev').click();
        if (e.key === 'ArrowRight') document.getElementById('lbNext').click();
    });

    let touchStartX = 0;
    overlay.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; });
    overlay.addEventListener('touchend',   e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) < 40) return;
        if (diff > 0) document.getElementById('lbNext').click();
        else          document.getElementById('lbPrev').click();
    });

});
</script>
@endpush
