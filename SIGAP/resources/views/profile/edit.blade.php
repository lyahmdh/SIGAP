@extends('layouts.app')

@section('title', 'Edit Profil')

@push('styles')
<style>
/* ─── REUSE PROFILE LAYOUT ────────────────────────── */
.profile-wrapper {
    display: flex;
    min-height: calc(100vh - 64px);
    background: var(--cream);
}

/* ── Sidebar (edit mode) ── */
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

/* Avatar with upload overlay */
.avatar-upload-wrap {
    position: relative;
    margin-bottom: 2rem;
    cursor: pointer;
}
.avatar-upload-img {
    width: 160px; height: 160px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.30);
    display: block;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
    transition: filter .2s;
}
.avatar-upload-initials {
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
    transition: filter .2s;
}
.avatar-upload-overlay {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: rgba(0,0,0,.40);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .3rem;
    opacity: 0;
    transition: opacity .2s;
}
.avatar-upload-wrap:hover .avatar-upload-overlay { opacity: 1; }
.avatar-upload-wrap:hover .avatar-upload-img,
.avatar-upload-wrap:hover .avatar-upload-initials { filter: brightness(.75); }
.avatar-upload-overlay i { font-size: 1.5rem; color: var(--white); }
.avatar-upload-overlay span { font-size: .75rem; color: rgba(255,255,255,.9); font-weight: 600; }

/* Form fields in sidebar */
.sidebar-form-label {
    font-size: .78rem;
    font-weight: 700;
    color: rgba(255,255,255,.70);
    text-transform: uppercase;
    letter-spacing: .07em;
    margin-bottom: .4rem;
    display: block;
    width: 100%;
    text-align: left;
}
.sidebar-form-input {
    width: 100%;
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.25);
    border-radius: var(--radius-sm);
    padding: .6rem .9rem;
    font-size: .875rem;
    font-family: var(--font-main);
    color: var(--white);
    transition: border-color .2s, background .2s;
    outline: none;
    margin-bottom: 1rem;
}
.sidebar-form-input::placeholder { color: rgba(255,255,255,.40); }
.sidebar-form-input:focus {
    border-color: rgba(255,255,255,.65);
    background: rgba(255,255,255,.18);
}
.sidebar-form-input.is-invalid {
    border-color: #FC8181;
}

.field-error-sidebar {
    font-size: .75rem;
    color: #FCA5A5;
    margin-top: -.6rem;
    margin-bottom: .75rem;
    display: flex;
    align-items: center;
    gap: .3rem;
}

/* Action buttons */
.btn-sidebar-save {
    width: 100%;
    padding: .75rem;
    background: rgba(255,255,255,.18);
    border: 2px solid rgba(255,255,255,.55);
    border-radius: var(--radius-sm);
    color: var(--white);
    font-weight: 700;
    font-size: .9rem;
    font-family: var(--font-main);
    cursor: pointer;
    transition: all .2s;
    margin-bottom: .65rem;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.btn-sidebar-save:hover {
    background: rgba(255,255,255,.28);
    border-color: var(--white);
}
.btn-sidebar-cancel {
    width: 100%;
    padding: .65rem;
    background: transparent;
    border: 2px solid rgba(255,255,255,.25);
    border-radius: var(--radius-sm);
    color: rgba(255,255,255,.70);
    font-weight: 600;
    font-size: .875rem;
    font-family: var(--font-main);
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.btn-sidebar-cancel:hover {
    border-color: rgba(255,255,255,.55);
    color: var(--white);
}

/* Password section toggle */
.password-section-toggle {
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(255,255,255,.15);
    width: 100%;
}
.password-toggle-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    background: none;
    border: none;
    color: rgba(255,255,255,.70);
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    font-family: var(--font-main);
    margin-bottom: .85rem;
    transition: color .2s;
}
.password-toggle-btn:hover { color: var(--white); }
.password-toggle-btn i { transition: transform .2s; }
.password-toggle-btn.open i { transform: rotate(180deg); }
.password-fields { display: none; }
.password-fields.show { display: block; }

/* ── Main content (riwayat tabel – sama seperti profile show) ── */
.profile-main {
    flex: 1;
    padding: 2.5rem 2.5rem 3rem;
    min-width: 0;
    overflow-x: hidden;
}
.riwayat-title {
    font-family: var(--font-display);
    font-size: 1.6rem;
    color: var(--green-dark);
    font-weight: 600;
    margin-bottom: 1.5rem;
}
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
.td-no   { color: var(--text-muted); font-weight: 600; font-size: .85rem; }
.td-title { font-weight: 600; color: var(--green-dark); }
.td-date  { color: var(--text-muted); font-size: .85rem; white-space: nowrap; }
.badge-status         { padding: .3em .8em; border-radius: 50px; font-size: .78rem; font-weight: 700; white-space: nowrap; }
.badge-masuk          { background:#FEF3C7; color:#92400E; }
.badge-diverifikasi   { background:#EDE9FE; color:#5B21B6; }
.badge-ditindaklanjuti{ background:#DBEAFE; color:#1E40AF; }
.badge-selesai        { background:#D1FAE5; color:#065F46; }
.badge-ditolak        { background:#FEE2E2; color:#991B1B; }
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
    }
    .avatar-upload-img,
    .avatar-upload-initials { width: 100px; height: 100px; font-size: 2.2rem; }
    .profile-wrapper { flex-direction: column; }
    .profile-main { padding: 1.5rem; }
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

$nameParts = explode(' ', trim($user->name ?? 'U'));
$initials  = strtoupper(substr($nameParts[0], 0, 1));
if (count($nameParts) > 1) $initials .= strtoupper(substr(end($nameParts), 0, 1));
@endphp

<div class="profile-wrapper">

    {{-- ── SIDEBAR (FORM EDIT) ─────────────────────── --}}
    <aside class="profile-sidebar">

        {{-- Avatar upload --}}
        <div class="avatar-upload-wrap" id="avatarWrap">
            <input type="file" id="photoInput" name="photo"
                   accept="image/jpeg,image/png"
                   style="display:none"
                   form="profileForm">

            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                     class="avatar-upload-img" id="avatarPreview"
                     alt="{{ $user->name }}">
            @else
                <div class="avatar-upload-initials" id="avatarInitials">{{ $initials }}</div>
            @endif

            <div class="avatar-upload-overlay">
                <i class="bi bi-camera-fill"></i>
                <span>Ganti Foto</span>
            </div>
        </div>

        {{-- Profile form --}}
        <form method="POST"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              id="profileForm"
              style="width:100%"
              novalidate>
            @csrf
            @method('PUT')

            {{-- Hidden photo (populated by JS) --}}

            {{-- Nama Lengkap --}}
            <label class="sidebar-form-label" for="nameInput">Nama Lengkap</label>
            <input type="text"
                   id="nameInput"
                   name="name"
                   class="sidebar-form-input @error('name') is-invalid @enderror"
                   placeholder="Masukkan nama lengkap"
                   value="{{ old('name', $user->name) }}"
                   required>
            @error('name')
            <div class="field-error-sidebar"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror

            {{-- Email --}}
            <label class="sidebar-form-label" for="emailInput">Email</label>
            <input type="email"
                   id="emailInput"
                   name="email"
                   class="sidebar-form-input @error('email') is-invalid @enderror"
                   placeholder="Masukkan Email"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
            <div class="field-error-sidebar"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror

            {{-- Password section (collapsible) --}}
            <div class="password-section-toggle">
                <button type="button" class="password-toggle-btn" id="passwordToggle">
                    <span><i class="bi bi-lock-fill me-2"></i>Ganti Password</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="password-fields" id="passwordFields">
                    <label class="sidebar-form-label" for="currentPassword">Password Saat Ini</label>
                    <input type="password"
                           id="currentPassword"
                           name="current_password"
                           class="sidebar-form-input @error('current_password') is-invalid @enderror"
                           placeholder="Password lama">
                    @error('current_password')
                    <div class="field-error-sidebar"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <label class="sidebar-form-label" for="newPassword">Password Baru</label>
                    <input type="password"
                           id="newPassword"
                           name="password"
                           class="sidebar-form-input @error('password') is-invalid @enderror"
                           placeholder="Password baru (min. 8 karakter)">
                    @error('password')
                    <div class="field-error-sidebar"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <label class="sidebar-form-label" for="confirmPassword">Konfirmasi Password</label>
                    <input type="password"
                           id="confirmPassword"
                           name="password_confirmation"
                           class="sidebar-form-input"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            {{-- Buttons --}}
            <div style="margin-top:1.5rem">
                <button type="submit" class="btn-sidebar-save">
                    <i class="bi bi-check-lg"></i> Simpan
                </button>
                <a href="{{ route('profile') }}" class="btn-sidebar-cancel">
                    <i class="bi bi-x-lg"></i> Batalkan
                </a>
            </div>

        </form>

    </aside>

    {{-- ── MAIN (Riwayat – read-only, sama seperti show.blade.php) ── --}}
    <main class="profile-main">

        {{-- Flash --}}
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 border-0 rounded-3 mb-3"
             style="font-size:.875rem">
            <i class="bi bi-check-circle-fill"></i>{{ session('success') }}
        </div>
        @endif

        <h1 class="riwayat-title">Riwayat Laporan</h1>

        <div class="riwayat-table-wrap">
            @if($reports->isEmpty())
                <div style="text-align:center; padding:3rem 2rem; color:var(--text-muted)">
                    <i class="bi bi-clipboard2-x" style="font-size:2.5rem; opacity:.3; display:block; margin-bottom:.75rem"></i>
                    <p>Belum ada laporan.</p>
                </div>
            @else
                <table class="riwayat-table">
                    <thead>
                        <tr>
                            <th style="width:52px">No</th>
                            <th>
                                <span style="display:inline-flex; align-items:center; gap:.25rem; cursor:pointer">
                                    Nama Jalan
                                </span>
                            </th>
                            <th>
                                <span style="display:inline-flex; align-items:center; gap:.25rem; cursor:pointer">
                                    Tanggal Laporan
                                    <i class="bi bi-arrow-down-up" style="font-size:.72rem; opacity:.75"></i>
                                </span>
                            </th>
                            <th>
                                <span style="display:inline-flex; align-items:center; gap:.25rem; cursor:pointer">
                                    Status
                                    <i class="bi bi-funnel-fill" style="font-size:.72rem; opacity:.75"></i>
                                </span>
                            </th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $i => $report)
                        @php
                        $statusLabel = [
                            'masuk'           => 'Masuk',
                            'diverifikasi'    => 'Diverifikasi',
                            'ditindaklanjuti' => 'Ditindaklanjuti',
                            'selesai'         => 'Selesai',
                            'ditolak'         => 'Ditolak',
                        ][$report->status] ?? ucfirst($report->status);
                        @endphp
                        <tr>
                            <td class="td-no">{{ $reports->firstItem() + $i }}</td>
                            <td class="td-title">{{ $report->title }}</td>
                            <td class="td-date">
                                {{ \Carbon\Carbon::parse($report->created_at)->isoFormat('D MMMM Y') }}
                            </td>
                            <td>
                                <span class="badge-status badge-{{ $report->status }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('laporan.show', $report->id) }}"
                                   class="btn-detail-riwayat">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($reports->hasPages())
                <div class="riwayat-footer">
                    <span>
                        Menampilkan
                        <strong>{{ $reports->firstItem() }}–{{ $reports->lastItem() }}</strong>
                        dari <strong>{{ $reports->total() }}</strong> laporan
                    </span>
                    {{ $reports->links('pagination::bootstrap-5') }}
                </div>
                @else
                <div class="riwayat-footer">
                    <span>Menampilkan <strong>{{ $reports->count() }}</strong> laporan</span>
                </div>
                @endif
            @endif
        </div>

    </main>

</div>{{-- /profile-wrapper --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── AVATAR UPLOAD PREVIEW ─────────────────────────────
    const avatarWrap    = document.getElementById('avatarWrap');
    const photoInput    = document.getElementById('photoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarInit    = document.getElementById('avatarInitials');

    avatarWrap.addEventListener('click', () => photoInput.click());

    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (!file) return;

        if (!['image/jpeg','image/png'].includes(file.type)) {
            alert('Format foto harus JPG atau PNG.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2MB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = e => {
            if (avatarPreview) {
                avatarPreview.src = e.target.result;
            } else {
                // Jika belum ada preview img (pakai initials), buat baru
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id  = 'avatarPreview';
                img.alt = 'Foto profil';
                img.className = 'avatar-upload-img';
                if (avatarInit) avatarInit.replaceWith(img);
            }
        };
        reader.readAsDataURL(file);
    });

    // ── PASSWORD SECTION TOGGLE ───────────────────────────
    const toggleBtn     = document.getElementById('passwordToggle');
    const passwordFields = document.getElementById('passwordFields');

    toggleBtn.addEventListener('click', () => {
        const isOpen = passwordFields.classList.toggle('show');
        toggleBtn.classList.toggle('open', isOpen);
    });

    // Auto-open jika ada error di password field
    const hasPasswordError = {{ $errors->has('current_password') || $errors->has('password') ? 'true' : 'false' }};
    if (hasPasswordError) {
        passwordFields.classList.add('show');
        toggleBtn.classList.add('open');
    }

});
</script>
@endpush