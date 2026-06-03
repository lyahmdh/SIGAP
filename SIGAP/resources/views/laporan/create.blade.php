@extends('layouts.app')

@section('title', 'Buat Laporan')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* ─── PAGE HEADER ─────────────────────────────────── */
.lapor-header {
    background: var(--cream);
    padding: 3.5rem 0 2rem;
    text-align: center;
}
.lapor-header h1 {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    color: var(--green-dark);
    font-weight: 600;
    margin-bottom: .4rem;
}
.lapor-header p {
    color: var(--text-muted);
    font-size: .95rem;
}

/* ─── FORM CARD ───────────────────────────────────── */
.form-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.form-card-header {
    background: linear-gradient(135deg, var(--brown-dark) 0%, var(--brown-mid) 100%);
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}
.form-card-header h2 {
    font-family: var(--font-display);
    font-size: 1.2rem;
    color: var(--white);
    font-weight: 600;
    margin: 0;
}
.form-card-header i {
    color: var(--brown-accent);
    font-size: 1.25rem;
}

.form-card-body {
    padding: 2rem;
}

/* ─── FORM ELEMENTS ───────────────────────────────── */
.form-label-sigap {
    font-size: .875rem;
    font-weight: 600;
    color: var(--green-dark);
    margin-bottom: .5rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.form-label-sigap .req { color: #E53E3E; }

.form-control-sigap,
.form-select-sigap {
    border: 1.5px solid var(--cream-dark);
    border-radius: var(--radius-sm);
    font-size: .9rem;
    padding: .65rem 1rem;
    background: var(--cream);
    color: var(--text-dark);
    width: 100%;
    transition: border-color .2s, box-shadow .2s;
    font-family: var(--font-main);
}
.form-control-sigap:focus,
.form-select-sigap:focus {
    outline: none;
    border-color: var(--green-light);
    box-shadow: 0 0 0 3px rgba(74,124,78,.12);
    background: var(--white);
}
.form-control-sigap::placeholder { color: #A0AEC0; }
textarea.form-control-sigap { resize: vertical; min-height: 110px; }

/* ─── MAP ─────────────────────────────────────────── */
.map-wrapper {
    border-radius: var(--radius-md);
    overflow: hidden;
    border: 1.5px solid var(--cream-dark);
    position: relative;
}
#laporan-map {
    height: 300px;
    width: 100%;
    z-index: 1;
}
.map-use-location {
    position: absolute;
    bottom: .75rem;
    right: .75rem;
    z-index: 999;
    background: var(--white);
    border: 1.5px solid var(--cream-dark);
    border-radius: var(--radius-sm);
    padding: .45rem .9rem;
    font-size: .8rem;
    font-weight: 600;
    color: var(--green-mid);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: .4rem;
    box-shadow: var(--shadow-sm);
    transition: all .2s;
}
.map-use-location:hover {
    background: var(--green-mid);
    color: var(--white);
    border-color: var(--green-mid);
}
.map-coords-display {
    margin-top: .5rem;
    font-size: .775rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: .4rem;
}

/* ─── SEVERITY SELECTOR ───────────────────────────── */
.severity-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .75rem;
}
.severity-option {
    position: relative;
}
.severity-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0; height: 0;
}
.severity-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    padding: 1.1rem .75rem;
    border: 2px solid var(--cream-dark);
    border-radius: var(--radius-md);
    cursor: pointer;
    background: var(--cream);
    transition: all .2s;
    text-align: center;
}
.severity-label i { font-size: 1.5rem; }
.severity-label .sev-title {
    font-weight: 700;
    font-size: .875rem;
}
.severity-label .sev-sub {
    font-size: .72rem;
    color: var(--text-muted);
}

/* States */
.severity-option input:checked + .severity-label {
    border-width: 2px;
    background: transparent;
}
/* Rendah */
.severity-option.sev-1 .severity-label i { color: var(--green-mid); }
.severity-option.sev-1 input:checked + .severity-label {
    border-color: var(--green-mid);
    background: rgba(45,90,46,.06);
}
.severity-option.sev-1 input:checked + .severity-label .sev-title { color: var(--green-mid); }
/* Sedang */
.severity-option.sev-2 .severity-label i { color: #D97706; }
.severity-option.sev-2 input:checked + .severity-label {
    border-color: #D97706;
    background: rgba(217,119,6,.06);
}
.severity-option.sev-2 input:checked + .severity-label .sev-title { color: #D97706; }
/* Mendesak */
.severity-option.sev-3 .severity-label i { color: #E53E3E; }
.severity-option.sev-3 input:checked + .severity-label {
    border-color: #E53E3E;
    background: rgba(229,62,62,.06);
}
.severity-option.sev-3 input:checked + .severity-label .sev-title { color: #E53E3E; }

.severity-label:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }

/* ─── IMAGE UPLOAD ────────────────────────────────── */
.upload-zone {
    border: 2px dashed var(--cream-dark);
    border-radius: var(--radius-md);
    background: var(--cream);
    padding: 1.5rem;
    cursor: pointer;
    transition: all .2s;
    min-height: 120px;
}
.upload-zone:hover { border-color: var(--green-light); background: rgba(74,124,78,.04); }
.upload-zone.drag-over { border-color: var(--green-mid); background: var(--green-pale); }

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    gap: .5rem;
    min-height: 80px;
}
.upload-placeholder i { font-size: 2rem; color: var(--green-light); }
.upload-placeholder .up-text { font-size: .875rem; font-weight: 600; color: var(--green-mid); }
.upload-placeholder .up-sub { font-size: .775rem; }

.preview-grid {
    display: flex;
    flex-wrap: wrap;
    gap: .6rem;
    margin-top: .75rem;
}
.preview-item {
    position: relative;
    width: 80px; height: 80px;
    border-radius: var(--radius-sm);
    overflow: hidden;
    border: 1.5px solid var(--cream-dark);
}
.preview-item img { width: 100%; height: 100%; object-fit: cover; }
.preview-item .remove-img {
    position: absolute;
    top: 2px; right: 2px;
    background: rgba(229,62,62,.85);
    color: var(--white);
    border: none;
    border-radius: 50%;
    width: 20px; height: 20px;
    font-size: .65rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background .2s;
}
.preview-item .remove-img:hover { background: #C53030; }
.upload-hint { font-size: .775rem; color: var(--text-muted); margin-top: .5rem; }

/* ─── ANONYMOUS TOGGLE ────────────────────────────── */
.anon-toggle-wrap {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: 1rem 1.25rem;
    background: var(--cream);
    border-radius: var(--radius-md);
    border: 1.5px solid var(--cream-dark);
}
.form-check-input-sigap {
    width: 1.15rem; height: 1.15rem;
    border: 2px solid var(--cream-dark);
    border-radius: 4px;
    cursor: pointer;
    accent-color: var(--green-mid);
    flex-shrink: 0;
}
.anon-info { font-size: .8rem; color: var(--text-muted); line-height: 1.5; }
.anon-info strong { color: var(--green-dark); }

/* ─── SUBMIT BUTTON ───────────────────────────────── */
.btn-submit-lapor {
    background: var(--green-mid);
    color: var(--white);
    border: none;
    border-radius: var(--radius-sm);
    padding: .75rem 2.5rem;
    font-weight: 700;
    font-size: 1rem;
    font-family: var(--font-main);
    cursor: pointer;
    transition: all .22s;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
}
.btn-submit-lapor:hover {
    background: var(--green-dark);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(45,90,46,.28);
}
.btn-cancel {
    background: transparent;
    color: var(--text-muted);
    border: 1.5px solid var(--cream-dark);
    border-radius: var(--radius-sm);
    padding: .75rem 1.5rem;
    font-weight: 600;
    font-size: .9rem;
    font-family: var(--font-main);
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.btn-cancel:hover { border-color: var(--text-muted); color: var(--text-dark); }

/* ─── STEPPER ─────────────────────────────────────── */
.stepper-section {
    margin: 5rem 0 5rem 0;
    background: var(--cream);
}
.stepper-wrap {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    gap: 0;
    position: relative;
}
.step-item {
    text-align: center;
    flex: 1;
    max-width: 160px;
    position: relative;
}
.step-item::before {
    content: '';
    position: absolute;
    top: 28px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: var(--cream-dark);
    z-index: 0;
}
.step-item:last-child::before { display: none; }

.step-circle {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--cream-dark);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto .75rem;
    position: relative;
    z-index: 1;
    font-size: 1.3rem;
    color: var(--text-muted);
    transition: all .25s;
}
.step-item.active .step-circle {
    background: var(--green-dark);
    color: var(--white);
    box-shadow: 0 4px 14px rgba(30,58,31,.30);
}
.step-title {
    font-weight: 700;
    font-size: .875rem;
    color: var(--green-dark);
    margin-bottom: .2rem;
}
.step-sub {
    font-size: .775rem;
    color: var(--text-muted);
    line-height: 1.5;
    padding: 0 .5rem;
}

/* ─── ALERT / VALIDATION ──────────────────────────── */
.alert-sigap {
    border-radius: var(--radius-md);
    padding: .9rem 1.25rem;
    font-size: .875rem;
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    margin-bottom: 1.25rem;
}
.alert-sigap-error {
    background: #FFF5F5;
    border: 1px solid #FEB2B2;
    color: #C53030;
}
.alert-sigap i { margin-top: .1rem; flex-shrink: 0; }

.field-error {
    font-size: .775rem;
    color: #E53E3E;
    margin-top: .3rem;
    display: flex;
    align-items: center;
    gap: .3rem;
}
.is-invalid { border-color: #FC8181 !important; }

/* ─── CHAR COUNTER ────────────────────────────────── */
.char-counter {
    font-size: .72rem;
    color: var(--text-muted);
    text-align: right;
    margin-top: .3rem;
    transition: color .2s;
}
.char-counter.warn  { color: #D97706; font-weight: 600; }
.char-counter.danger { color: #E53E3E; font-weight: 700; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="lapor-header">
    <div class="container">
        <h1>SIGAP</h1>
        <p>Website pelaporan fasilitas yang rusak</p>
    </div>
</div>

{{-- MAIN FORM --}}
<div style="background: var(--cream);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                {{-- Error bag --}}
                @if($errors->any())
                <div class="alert-sigap alert-sigap-error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <div class="fw-700 mb-1">Terdapat kesalahan pada form:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Session error --}}
                @if(session('error'))
                <div class="alert-sigap alert-sigap-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="form-card">
                    {{-- Card Header --}}
                    <div class="form-card-header">
                        <i class="bi bi-megaphone-fill"></i>
                        <h2>Sampaikan Laporan Anda</h2>
                    </div>

                    {{-- Card Body --}}
                    <div class="form-card-body">
                        <form method="POST"
                              action="{{ route('lapor.store') }}"
                              enctype="multipart/form-data"
                              id="reportForm"
                              novalidate>
                            @csrf

                            {{-- ── Kategori ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-tag" style="color:var(--green-mid)"></i>
                                    Kategori Laporan <span class="req">*</span>
                                </label>
                                <select name="category_id"
                                        class="form-select-sigap @error('category_id') is-invalid @enderror"
                                        required>
                                    <option value="" disabled selected>Pilih kategori…</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Judul ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-pencil" style="color:var(--green-mid)"></i>
                                    Judul Laporan <span class="req">*</span>
                                </label>
                                <input type="text"
                                    name="title"
                                    id="titleInput"
                                    value="{{ old('title') }}"
                                    class="form-control-sigap @error('title') is-invalid @enderror"
                                    placeholder="Contoh: Jalan Berlubang di Jl. Veteran"
                                    maxlength="80"
                                    required>
                                <div class="char-counter" id="titleCounter">
                                    <span id="titleCount">{{ strlen(old('title', '')) }}</span>/80 karakter
                                </div>
                                @error('title')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Deskripsi ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-card-text" style="color:var(--green-mid)"></i>
                                    Deskripsi Laporan <span class="req">*</span>
                                </label>
                                <textarea name="description"
                                          class="form-control-sigap @error('description') is-invalid @enderror"
                                          placeholder="Jelaskan kondisi kerusakan secara detail, termasuk dampaknya bagi warga…"
                                          required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Lokasi via Peta ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-geo-alt" style="color:var(--green-mid)"></i>
                                    Lokasi Kejadian <span class="req">*</span>
                                </label>
                                <div class="map-wrapper">
                                    <div id="laporan-map"></div>
                                    <button type="button" class="map-use-location" id="useMyLocation">
                                        <i class="bi bi-crosshair2"></i>
                                        Gunakan Lokasi Saat Ini
                                    </button>
                                </div>
                                <div class="map-coords-display" id="coordsDisplay" style="display:none">
                                    <i class="bi bi-pin-map-fill" style="color:var(--green-mid)"></i>
                                    <span id="coordsText"></span>
                                </div>
                                @error('latitude')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>Lokasi belum dipilih di peta.</div>
                                @enderror

                                {{-- Hidden inputs ──────────────────────── --}}
                                <input type="hidden" name="latitude"      id="latInput"      value="{{ old('latitude') }}">
                                <input type="hidden" name="longitude"     id="lngInput"      value="{{ old('longitude') }}">
                                <input type="hidden" name="location_name" id="locationName"  value="{{ old('location_name') }}">
                                <input type="hidden" name="district"      id="districtInput" value="{{ old('district') }}">
                                <input type="hidden" name="subdistrict"   id="subdistrictInput" value="{{ old('subdistrict') }}">
                            </div>

                            {{-- ── Detail Lokasi ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-geo" style="color:var(--green-mid)"></i>
                                    Detail Lokasi <span class="req">*</span>
                                </label>
                                <textarea
                                    name="location_detail"
                                    id="locationDetailInput"
                                    class="form-control-sigap @error('location_detail') is-invalid @enderror"
                                    placeholder="Contoh: Depan SDN 01, sebelah minimarket, dekat lampu merah..."
                                    rows="3"
                                    maxlength="70"
                                    required>{{ old('location_detail') }}</textarea>
                                <div class="char-counter" id="locationDetailCounter">
                                    <span id="locationDetailCount">{{ strlen(old('location_detail', '')) }}</span>/70 karakter
                                </div>
                                <div class="upload-hint" style="margin-top:.25rem">
                                    <i class="bi bi-info-circle"></i>
                                    Jelaskan lokasi secara lebih spesifik agar petugas lebih mudah menemukan titik kerusakan.
                                </div>
                                @error('location_detail')
                                <div class="field-error">
                                    <i class="bi bi-exclamation-circle"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>

                            {{-- ── Tingkat Urgensi (Severity) ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-exclamation-diamond" style="color:var(--green-mid)"></i>
                                    Tingkat Urgensi <span class="req">*</span>
                                </label>
                                <div class="severity-grid">
                                    <div class="severity-option sev-1">
                                        <input type="radio" name="severity" id="sev1" value="1"
                                            {{ old('severity', '1') == '1' ? 'checked' : '' }}>
                                        <label class="severity-label" for="sev1">
                                            <i class="bi bi-info-circle-fill"></i>
                                            <span class="sev-title">Rendah</span>
                                            <span class="sev-sub">Informasi umum</span>
                                        </label>
                                    </div>
                                    <div class="severity-option sev-2">
                                        <input type="radio" name="severity" id="sev2" value="2"
                                            {{ old('severity') == '2' ? 'checked' : '' }}>
                                        <label class="severity-label" for="sev2">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <span class="sev-title">Sedang</span>
                                            <span class="sev-sub">Perlu diperhatikan</span>
                                        </label>
                                    </div>
                                    <div class="severity-option sev-3">
                                        <input type="radio" name="severity" id="sev3" value="3"
                                            {{ old('severity') == '3' ? 'checked' : '' }}>
                                        <label class="severity-label" for="sev3">
                                            <i class="bi bi-shield-exclamation"></i>
                                            <span class="sev-title">Mendesak</span>
                                            <span class="sev-sub">Bahaya keselamatan</span>
                                        </label>
                                    </div>
                                </div>
                                @error('severity')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Upload Foto ── --}}
                            <div class="mb-4">
                                <label class="form-label-sigap">
                                    <i class="bi bi-camera" style="color:var(--green-mid)"></i>
                                    Unggah Bukti Laporan <span class="req">*</span>
                                </label>
                                <div class="upload-zone" id="uploadZone">
                                    <input type="file"
                                           name="images[]"
                                           id="imageInput"
                                           accept="image/jpeg,image/png"
                                           multiple
                                           style="display:none">
                                    <div class="upload-placeholder" id="uploadPlaceholder">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                        <span class="up-text">Klik atau seret foto ke sini</span>
                                        <span class="up-sub">Maks. 4 foto, format JPG/PNG (maks. 2MB per file)</span>
                                    </div>
                                    <div class="preview-grid" id="previewGrid"></div>
                                </div>
                                <div class="upload-hint">
                                    <i class="bi bi-info-circle"></i>
                                    Foto yang jelas akan mempercepat proses verifikasi laporan Anda.
                                </div>
                                @error('images')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                <div class="field-error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ── Separator ── --}}
                            <hr style="border-color:var(--cream-dark); margin: 1.5rem 0;">

                            {{-- ── Anonim + Submit ── --}}
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                                <div class="anon-toggle-wrap flex-fill">
                                    <input type="checkbox"
                                           class="form-check-input-sigap"
                                           name="is_anonymous"
                                           id="isAnonymous"
                                           value="1"
                                           {{ old('is_anonymous') ? 'checked' : '' }}>
                                    <div>
                                        <label for="isAnonymous" class="fw-700" style="font-size:.875rem; cursor:pointer; color:var(--green-dark)">
                                            Anonim
                                        </label>
                                        <div class="anon-info">Nama Anda tidak akan ditampilkan ke publik</div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="{{ route('rekap') }}" class="btn-cancel">Batal</a>
                                    <button type="submit" class="btn-submit-lapor" id="submitBtn">
                                        <i class="bi bi-send-fill"></i>
                                        Lapor
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>{{-- /form-card-body --}}
                </div>{{-- /form-card --}}

            </div>
        </div>
    </div>
</div>

{{-- STEPPER INFO ─────────────────────────────────── --}}
<section class="stepper-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="stepper-wrap">
                    <div class="step-item active">
                        <div class="step-circle"><i class="bi bi-pencil-square"></i></div>
                        <div class="step-title">Tulis Laporan</div>
                        <div class="step-sub">Laporkan keluhan atau aspirasi anda dengan jelas dan lengkap</div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle"><i class="bi bi-book"></i></div>
                        <div class="step-title">Proses Verifikasi</div>
                        <div class="step-sub">Dalam 3 hari, laporan Anda akan diverifikasi</div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle"><i class="bi bi-fire"></i></div>
                        <div class="step-title">Proses Tindak Lanjut</div>
                        <div class="step-sub">Selanjutnya instansi akan menindaklanjuti laporan Anda</div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle"><i class="bi bi-check-circle"></i></div>
                        <div class="step-title">Selesai</div>
                        <div class="step-sub">Laporan Anda akan terus ditindaklanjuti hingga terselesaikan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── MAP INIT ──────────────────────────────────────────
    const defaultLat = {{ old('latitude', -7.9666) }};
    const defaultLng = {{ old('longitude', 112.6326) }};

    const map    = L.map('laporan-map').setView([defaultLat, defaultLng], 14);
    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    const latInput      = document.getElementById('latInput');
    const lngInput      = document.getElementById('lngInput');
    const locationNameInput = document.getElementById('locationName');
    const districtInput = document.getElementById('districtInput');
    const coordsDisplay = document.getElementById('coordsDisplay');
    const coordsText    = document.getElementById('coordsText');
    
    // ── CHAR COUNTERS ──────────────────────────────────────
    function initCharCounter(inputId, countId, counterId, max) {
        const input   = document.getElementById(inputId);
        const count   = document.getElementById(countId);
        const counter = document.getElementById(counterId);
        if (!input) return;

        function update() {
            const len = input.value.length;
            count.textContent = len;

            counter.classList.remove('warn', 'danger');
            if (len >= max)           counter.classList.add('danger');
            else if (len >= max * .8) counter.classList.add('warn');
        }

        input.addEventListener('input', update);
        update(); // init dari old() value
    }

    initCharCounter('titleInput',          'titleCount',          'titleCounter',          80);
    initCharCounter('locationDetailInput', 'locationDetailCount', 'locationDetailCounter', 70);

    // ── KECAMATAN DETECTOR (IF ELSE VERSION) ──────────────
    function getKecamatan(lat, lng) {

        // Kedungkandang (selatan - timur)
        if (lat < -7.98 && lng > 112.65) {
            return "Kedungkandang";
        }

        // Lowokwaru (utara - barat)
        else if (lat < -7.93 && lng < 112.62) {
            return "Lowokwaru";
        }

        // Klojen (pusat kota)
        else if (lat >= -7.97 && lat <= -7.95 && lng >= 112.60 && lng <= 112.65) {
            return "Klojen";
        }

        // Blimbing (utara - timur)
        else if (lat >= -7.93 && lng > 112.62) {
            return "Blimbing";
        }

        // default fallback
        else {
            return "Sukun";
        }
    }

    // ── UPDATE COORDS ─────────────────────────────────────
    function updateCoords(lat, lng) {
        latInput.value = lat.toFixed(8);
        lngInput.value = lng.toFixed(8);

        coordsDisplay.style.display = 'flex';
        coordsText.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        // SET DISTRICT FROM IF-ELSE
        const district = getKecamatan(lat, lng);
        districtInput.value = district;

        // optional display tambahan
        coordsText.textContent += ` – ${district}`;
    }

    // ── INIT FROM OLD VALUE ───────────────────────────────
    if (latInput.value && lngInput.value) {
        updateCoords(parseFloat(latInput.value), parseFloat(lngInput.value));
        marker.setLatLng([parseFloat(latInput.value), parseFloat(lngInput.value)]);
        map.setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 16);
    }

    // ── CLICK MAP ─────────────────────────────────────────
    map.on('click', e => {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    // ── DRAG MARKER ───────────────────────────────────────
    marker.on('dragend', e => {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    // ── CURRENT LOCATION ──────────────────────────────────
    document.getElementById('useMyLocation').addEventListener('click', () => {
        if (!navigator.geolocation) {
            return alert('Geolokasi tidak didukung browser Anda.');
        }

        navigator.geolocation.getCurrentPosition(pos => {
            const { latitude: lat, longitude: lng } = pos.coords;

            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
            updateCoords(lat, lng);

        }, () => {
            alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.');
        });
    });

    // ── IMAGE UPLOAD ───────────────────────────────────────
    const uploadZone        = document.getElementById('uploadZone');
    const imageInput        = document.getElementById('imageInput');
    const previewGrid       = document.getElementById('previewGrid');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');

    let selectedFiles = [];

    uploadZone.addEventListener('click', e => {
        if (!e.target.closest('.remove-img')) imageInput.click();
    });

    uploadZone.addEventListener('dragover', e => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('drag-over');
    });

    uploadZone.addEventListener('drop', e => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        addFiles(Array.from(e.dataTransfer.files));
    });

    imageInput.addEventListener('change', () => {
        addFiles(Array.from(imageInput.files));
    });

    function addFiles(files) {
        files.forEach(file => {
            if (selectedFiles.length >= 5) return;
            if (!['image/jpeg','image/png'].includes(file.type)) return;
            if (file.size > 2 * 1024 * 1024) return;

            selectedFiles.push(file);
        });

        renderPreviews();
        syncFileInput();
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';
        uploadPlaceholder.style.display = selectedFiles.length ? 'none' : 'flex';

        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();

            reader.onload = e => {
                const item = document.createElement('div');
                item.className = 'preview-item';

                item.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button class="remove-img" type="button" data-idx="${i}">
                        <i class="bi bi-x"></i>
                    </button>
                `;

                item.querySelector('.remove-img').addEventListener('click', ev => {
                    ev.stopPropagation();
                    selectedFiles.splice(parseInt(ev.currentTarget.dataset.idx), 1);
                    renderPreviews();
                    syncFileInput();
                });

                previewGrid.appendChild(item);
            };

            reader.readAsDataURL(file);
        });
    }

    function syncFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        imageInput.files = dt.files;
    }

    // ── SUBMIT GUARD ───────────────────────────────────────
    document.getElementById('reportForm').addEventListener('submit', function(e) {

        if (!latInput.value || !lngInput.value) {
            e.preventDefault();
            alert('Harap pilih lokasi pada peta terlebih dahulu.');
            return;
        }

        if (selectedFiles.length === 0) {
            e.preventDefault();
            alert('Harap unggah minimal 1 foto bukti.');
            return;
        }

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim…';
    });

});
</script>
@endpush
