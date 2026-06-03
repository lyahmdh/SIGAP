@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    /* ─── HERO ─────────────────────────────────────────── */
    .hero-section {
        position: relative;
        min-height: 88vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: var(--green-dark);
    }

    .hero-bg {
        position: absolute;
        inset: 0;
        background-image: url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1600&q=80');
        background-size: cover;
        background-position: center 30%;
        opacity: .32;
        filter: saturate(.7);
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(30,58,31,.92) 0%,
            rgba(30,58,31,.75) 45%,
            rgba(61,28,11,.65) 100%
        );
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 6rem 0 4rem;
    }

    .hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(224,123,58,.18);
        border: 1px solid rgba(224,123,58,.35);
        color: var(--brown-accent);
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        padding: .35rem .85rem;
        border-radius: 50px;
        margin-bottom: 1.25rem;
    }

    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.4rem, 5.5vw, 4rem);
        color: var(--white);
        line-height: 1.18;
        font-weight: 600;
        margin-bottom: 1.25rem;
    }

    .hero-title .accent { color: var(--brown-accent); }

    .hero-subtitle {
        font-size: 1.05rem;
        color: rgba(255,255,255,.75);
        max-width: 520px;
        line-height: 1.7;
        margin-bottom: 2rem;
    }

    .hero-cta-group { display: flex; gap: 1rem; flex-wrap: wrap; }

    .btn-hero-primary {
        background: var(--brown-accent);
        color: var(--white);
        border: none;
        border-radius: var(--radius-sm);
        padding: .8rem 2rem;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .22s;
        box-shadow: 0 4px 16px rgba(224,123,58,.35);
    }
    .btn-hero-primary:hover {
        background: var(--brown-warm);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(224,123,58,.45);
    }

    .btn-hero-outline {
        background: transparent;
        color: var(--white);
        border: 2px solid rgba(255,255,255,.45);
        border-radius: var(--radius-sm);
        padding: .8rem 1.75rem;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .22s;
    }
    .btn-hero-outline:hover {
        border-color: var(--white);
        background: rgba(255,255,255,.10);
        color: var(--white);
    }

    /* ─── STATS STRIP ─────────────────────────────────── */
    .stats-strip {
        position: relative;
        z-index: 3;
        margin-top: -3rem;
    }

    .stats-card {
        background: linear-gradient(135deg, var(--brown-dark) 0%, var(--brown-mid) 100%);
        border-radius: var(--radius-lg);
        padding: 2rem 1.5rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 1rem;
        box-shadow: var(--shadow-lg);
    }

    .stat-item {
        text-align: center;
        flex: 1;
        min-width: 100px;
        padding: .5rem;
        border-right: 1px solid rgba(255,255,255,.12);
    }
    .stat-item:last-child { border-right: none; }

    .stat-number {
        font-family: var(--font-display);
        font-size: 2.4rem;
        font-weight: 600;
        color: var(--white);
        line-height: 1;
        margin-bottom: .35rem;
    }

    .stat-label {
        font-size: .78rem;
        color: rgba(255,255,255,.60);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .07em;
    }

    /* ─── ABOUT SECTION ───────────────────────────────── */
    .about-section { padding: 6rem 0; }

    .about-img-wrapper {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    .about-img-wrapper img {
        width: 100%;
        height: 420px;
        object-fit: cover;
    }
    .about-img-badge {
        position: absolute;
        bottom: 1.5rem;
        left: 1.5rem;
        background: var(--brown-accent);
        color: var(--white);
        border-radius: var(--radius-sm);
        padding: .65rem 1.1rem;
        font-weight: 700;
        font-size: .875rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .section-eyebrow {
        font-size: .775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: var(--brown-accent);
        margin-bottom: .6rem;
    }

    .section-title {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        color: var(--green-dark);
        font-weight: 600;
        line-height: 1.25;
        margin-bottom: 1rem;
    }

    .section-underline {
        width: 48px;
        height: 3px;
        background: var(--brown-accent);
        border-radius: 4px;
        margin-bottom: 1.25rem;
    }

    .about-body {
        font-size: .975rem;
        color: var(--text-muted);
        line-height: 1.8;
    }

    /* ─── FEATURES ────────────────────────────────────── */
    .features-section {
        padding: 5rem 0 6rem;
        background-color: var(--white);
    }

    .features-section .section-title { text-align: center; }
    .features-section .section-underline { margin: 0 auto 3rem; }
    .features-section .section-eyebrow { text-align: center; }

    .feature-card {
        background: var(--cream);
        border-radius: var(--radius-md);
        padding: 2rem 1.75rem;
        height: 100%;
        border: 1px solid var(--cream-dark);
        transition: all .25s;
        position: relative;
        overflow: hidden;
    }
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--green-mid), var(--brown-accent));
        opacity: 0;
        transition: opacity .25s;
    }
    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    .feature-card:hover::before { opacity: 1; }

    .feature-icon {
        width: 56px; height: 56px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--green-pale), rgba(74,124,78,.15));
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem;
        font-size: 1.5rem;
        color: var(--green-mid);
    }

    .feature-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--green-dark);
        margin-bottom: .6rem;
    }

    .feature-body {
        font-size: .875rem;
        color: var(--text-muted);
        line-height: 1.7;
    }

    /* ─── FAQ ─────────────────────────────────────────── */
    .faq-section {
        padding: 5rem 0 6rem;
        background-color: var(--cream);
    }

    .faq-section .section-title { text-align: center; }
    .faq-section .section-underline { margin: 0 auto 3rem; }
    .faq-section .section-eyebrow { text-align: center; }

    .faq-wrap { max-width: 760px; margin: 0 auto; }

    .accordion-item {
        background: var(--white);
        border: 1px solid var(--cream-dark) !important;
        border-radius: var(--radius-md) !important;
        margin-bottom: .75rem;
        overflow: hidden;
    }

    .accordion-button {
        background: var(--white) !important;
        color: var(--green-dark) !important;
        font-weight: 600;
        font-size: .975rem;
        padding: 1.1rem 1.4rem;
        border-radius: var(--radius-md) !important;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
        color: var(--brown-accent) !important;
        border-bottom: 1px solid var(--cream-dark);
        border-radius: var(--radius-md) var(--radius-md) 0 0 !important;
    }

    .accordion-button::after {
        filter: invert(42%) sepia(52%) saturate(800%) hue-rotate(5deg) brightness(95%);
    }

    .accordion-body {
        font-size: .9rem;
        color: var(--text-muted);
        line-height: 1.75;
        padding: 1.1rem 1.4rem;
    }

    /* ─── CTA BANNER ──────────────────────────────────── */
    .cta-section {
        background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
        padding: 5rem 0;
        text-align: center;
    }
    .cta-section .cta-title {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3.5vw, 2.5rem);
        color: var(--white);
        margin-bottom: 1rem;
    }
    .cta-section .cta-sub {
        color: rgba(255,255,255,.70);
        font-size: 1rem;
        max-width: 480px;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }

    /* ─── ANIMATIONS ──────────────────────────────────── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .fade-up {
        animation: fadeUp .65s ease both;
    }
    .delay-1 { animation-delay: .1s; }
    .delay-2 { animation-delay: .22s; }
    .delay-3 { animation-delay: .34s; }
    .delay-4 { animation-delay: .46s; }
</style>
@endpush

@section('content')

{{-- ─────────────────────────────────────────────────────────────
     HERO
────────────────────────────────────────────────────────────── --}}
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-eyebrow fade-up">
                    <i class="bi bi-shield-check"></i>
                    Platform Aduan Fasilitas Publik
                </div>
                <h1 class="hero-title fade-up delay-1">
                    Laporkan. Pantau. <span class="accent">Wujudkan</span><br>Perubahan Nyata.
                </h1>
                <p class="hero-subtitle fade-up delay-2">
                    SIGAP memudahkan warga melaporkan kerusakan fasilitas umum secara terstruktur, dan memastikan pemerintah menindaklanjuti berdasarkan urgensi yang terukur.
                </p>
                <div class="hero-cta-group fade-up delay-3">
                    <a href="{{ route('lapor.create') ?? '/lapor' }}" class="btn-hero-primary">
                        <i class="bi bi-megaphone-fill"></i>
                        Buat Laporan
                    </a>
                    <a href="{{ route('rekap') ?? '/rekap' }}" class="btn-hero-outline">
                        <i class="bi bi-list-ul"></i>
                        Lihat Laporan Publik
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     STATS STRIP
────────────────────────────────────────────────────────────── --}}
<section class="stats-strip">
    <div class="container">
        <div class="stats-card">
            <div class="stat-item">
                <div class="stat-number" data-count="{{ $stats['total_anggaran'] ?? 128 }}">
                    {{ $stats['total_anggaran'] ?? '128' }}
                </div>
                <div class="stat-label">Total Laporan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="{{ $stats['proyek_aktif'] ?? 42 }}">
                    {{ $stats['proyek_aktif'] ?? '42' }}
                </div>
                <div class="stat-label">Proyek Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="{{ $stats['proyek_selesai'] ?? 74 }}">
                    {{ $stats['proyek_selesai'] ?? '74' }}
                </div>
                <div class="stat-label">Proyek Selesai</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="{{ $stats['total_laporan'] ?? 312 }}">
                    {{ $stats['total_laporan'] ?? '312' }}
                </div>
                <div class="stat-label">Laporan Diterima</div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     TENTANG KAMI
────────────────────────────────────────────────────────────── --}}
<section class="about-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-5">
                <div class="about-img-wrapper">
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=800&q=80"
                         alt="Warga dan fasilitas desa">
                    <div class="about-img-badge">
                        <i class="bi bi-award-fill"></i>
                        Transparan & Akuntabel
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="section-eyebrow">Tentang Kami</div>
                <h2 class="section-title">Apa itu <span style="color:var(--brown-accent);">SIGAP</span>?</h2>
                <div class="section-underline"></div>
                <p class="about-body">
                    <strong>SIGAP</strong> (Sistem Informasi Gangguan & Aduan Publik) adalah platform digital yang menjembatani warga dan pemerintah Kota Malang dalam penanganan kerusakan fasilitas publik.
                </p>
                <p class="about-body mt-3">
                    Melalui SIGAP, warga dapat melaporkan masalah lengkap dengan foto dan lokasi peta. Sistem secara otomatis menghitung <em>priority score</em> berdasarkan tingkat keparahan, jumlah pelapor, dan waktu tunggu — sehingga penanganan dilakukan berdasarkan urgensi yang terukur, bukan sekadar urutan datang.
                </p>
                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-3">
                            <div style="color:var(--green-mid); font-size:1.4rem; margin-top:.1rem;"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="fw-700" style="font-size:.9rem; color:var(--green-dark);">Laporan Terstruktur</div>
                                <div style="font-size:.82rem; color:var(--text-muted);">Dilengkapi foto, peta, dan tingkat keparahan</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-3">
                            <div style="color:var(--green-mid); font-size:1.4rem; margin-top:.1rem;"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="fw-700" style="font-size:.9rem; color:var(--green-dark);">Transparansi Penuh</div>
                                <div style="font-size:.82rem; color:var(--text-muted);">Status dan update proyek bisa diakses publik</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-3">
                            <div style="color:var(--green-mid); font-size:1.4rem; margin-top:.1rem;"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="fw-700" style="font-size:.9rem; color:var(--green-dark);">Prioritas Otomatis</div>
                                <div style="font-size:.82rem; color:var(--text-muted);">Sistem menghitung priority score secara cerdas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-start gap-3">
                            <div style="color:var(--green-mid); font-size:1.4rem; margin-top:.1rem;"><i class="bi bi-check-circle-fill"></i></div>
                            <div>
                                <div class="fw-700" style="font-size:.9rem; color:var(--green-dark);">Notifikasi Email</div>
                                <div style="font-size:.82rem; color:var(--text-muted);">Warga diberitahu setiap ada update laporan</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     FITUR UNGGULAN
────────────────────────────────────────────────────────────── --}}
<section class="features-section">
    <div class="container">
        <div class="section-eyebrow">Mengapa SIGAP?</div>
        <h2 class="section-title">Fitur yang Membuat Perbedaan</h2>
        <div class="section-underline"></div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div class="feature-title">Pelaporan Berbasis Lokasi</div>
                    <p class="feature-body">Laporkan kerusakan fasilitas publik dengan menentukan titik lokasi langsung pada peta interaktif.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-camera-fill"></i></div>
                    <div class="feature-title">Upload Foto Bukti</div>
                    <p class="feature-body">Lampirkan foto kondisi fasilitas sebagai bukti pendukung laporan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-sort-numeric-down"></i></div>
                    <div class="feature-title">Priority Score Otomatis</div>
                    <p class="feature-body">Sistem menghitung prioritas penanganan berdasarkan severity, report count, location importance, dan time waiting.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-arrow-repeat"></i></div>
                    <div class="feature-title">Status Tracking</div>
                    <p class="feature-body">Pantau perkembangan laporan mulai dari laporan masuk, diverifikasi, ditindaklanjuti, sampai selesai.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    <div class="feature-title">Deteksi Laporan Serupa</div>
                    <p class="feature-body">Membantu mengelompokkan laporan yang berada pada lokasi yang sama sehingga prioritas lebih akurat.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="bi bi-eye-fill"></i></div>
                    <div class="feature-title">Transparansi Penanganan</div>
                    <p class="feature-body">Seluruh pengguna dapat melihat status, perkembangan, dan tindak lanjut laporan secara terbuka.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     CARA KERJA
────────────────────────────────────────────────────────────── --}}
<section style="padding:5rem 0; background:var(--cream);">
    <div class="container">
        <div class="section-eyebrow text-center">Alur Penggunaan</div>
        <h2 class="section-title text-center">Bagaimana Cara Kerjanya?</h2>
        <div class="section-underline" style="margin:0 auto 3rem;"></div>

        <div class="row g-4 justify-content-center">
            @php
            $steps = [
                ['icon'=>'bi-pencil-square',    'num'=>'01', 'title'=>'Buat Laporan',       'body'=>'Isi formulir laporan lengkap dengan foto bukti, pilih lokasi di peta, dan tentukan tingkat keparahan kerusakan.'],
                ['icon'=>'bi-search',            'num'=>'02', 'title'=>'Verifikasi Admin',   'body'=>'Admin memverifikasi laporan dan sistem menghitung priority score berdasarkan severity, report count, location importance, dan time waiting.'],
                ['icon'=>'bi-tools',             'num'=>'03', 'title'=>'Tindak Lanjut',      'body'=>'Pemerintah menindaklanjuti laporan dengan prioritas tertinggi. Progress ditambahkan secara berkala disertai foto lapangan.'],
                ['icon'=>'bi-check-circle-fill', 'num'=>'04', 'title'=>'Selesai & Laporan',  'body'=>'Setelah perbaikan selesai, status diupdate ke "Selesai" dan warga menerima notifikasi email konfirmasi.'],
            ];
            @endphp

            @foreach($steps as $i => $step)
            <div class="col-sm-6 col-lg-3">
                <div class="text-center p-3">
                    <div style="
                        width:72px; height:72px;
                        border-radius:50%;
                        background:linear-gradient(135deg,var(--green-mid),var(--green-light));
                        display:flex; align-items:center; justify-content:center;
                        margin:0 auto 1rem;
                        font-size:1.75rem;
                        color:var(--white);
                        box-shadow:0 6px 18px rgba(45,90,46,.28);
                    ">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </div>
                    <div style="
                        font-size:.7rem; font-weight:800; letter-spacing:.14em;
                        color:var(--brown-accent); text-transform:uppercase; margin-bottom:.4rem;
                    ">LANGKAH {{ $step['num'] }}</div>
                    <div class="fw-700 mb-2" style="font-size:1rem; color:var(--green-dark);">{{ $step['title'] }}</div>
                    <p style="font-size:.855rem; color:var(--text-muted); line-height:1.7;">{{ $step['body'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     FAQ
────────────────────────────────────────────────────────────── --}}
<section class="faq-section">
    <div class="container">
        <div class="section-eyebrow">Pertanyaan Umum</div>
        <h2 class="section-title">Frequently Asked Questions</h2>
        <div class="section-underline"></div>

        <div class="faq-wrap">
            <div class="accordion" id="faqAccordion">

                @php
                $faqs = [
                    [
                        'q' => 'Bagaimana cara melaporkan masalah fasilitas publik?',
                        'a' => 'Klik tombol "Buat Laporan" di beranda atau menu "Lapor". Anda perlu masuk akun terlebih dahulu. Isi formulir dengan judul, deskripsi, foto bukti (maks. 5 foto), pilih lokasi di peta, dan tentukan tingkat keparahan. Laporan akan langsung masuk ke sistem.'
                    ],
                    [
                        'q' => 'Apakah laporan bisa dikirim secara anonim?',
                        'a' => 'Ya. Saat membuat laporan, tersedia opsi "Laporan Anonim". Jika diaktifkan, nama pelapor tidak akan ditampilkan kepada publik. Namun data Anda tetap tersimpan untuk keperluan tindak lanjut oleh admin.'
                    ],
                    [
                        'q' => 'Siapa saja yang dapat menggunakan SIGAP?',
                        'a' => 'SIGAP dapat diakses oleh semua kalangan. Masyarakat umum (tamu) dapat melihat daftar laporan dan detail proyek tanpa perlu mendaftar. Untuk membuat laporan, Anda perlu mendaftarkan akun terlebih dahulu.'
                    ],
                    [
                        'q' => 'Apakah data yang ditampilkan di SIGAP terpercaya?',
                        'a' => 'Setiap laporan masuk akan diverifikasi oleh admin sebelum ditindaklanjuti. Laporan yang tidak valid atau duplikat akan ditolak. Pembaruan status dan update proyek hanya dapat dilakukan oleh admin yang terverifikasi.'
                    ],
                    [
                        'q' => 'Apakah masyarakat bisa memantau perkembangan laporan?',
                        'a' => 'Ya. Setiap laporan memiliki halaman detail yang dapat diakses publik. Di sana Anda dapat melihat status terkini, priority score, komentar warga, dan update proyek dari admin beserta foto progress pekerjaan.'
                    ],
                    [
                        'q' => 'Apa itu Priority Score?',
                        'a' => 'Priority Score adalah nilai prioritas yang digunakan untuk menentukan urutan penanganan laporan. Nilai ini dihitung berdasarkan tingkat kerusakan, jumlah laporan serupa di lokasi yang sama, dan lamanya laporan menunggu penanganan.'
                    ],
                ];
                @endphp

                @foreach($faqs as $i => $faq)
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq{{ $i }}"
                                aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                aria-controls="faq{{ $i }}">
                            {{ $faq['q'] }}
                        </button>
                    </h3>
                    <div id="faq{{ $i }}"
                         class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ $faq['a'] }}</div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

{{-- ─────────────────────────────────────────────────────────────
     CTA BANNER
────────────────────────────────────────────────────────────── --}}
<section class="cta-section">
    <div class="container">

        @guest
            <h2 class="cta-title">Bantu Wujudkan Pelayanan Publik yang Lebih Responsif</h2>
            <p class="cta-sub">
                Laporkan kerusakan fasilitas publik di sekitar Anda dan pantau proses penanganannya secara transparan.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    <i class="bi bi-person-plus-fill"></i>
                    Daftar Sekarang
                </a>

                <a href="{{ route('rekap') }}" class="btn-hero-outline">
                    <i class="bi bi-search"></i>
                    Lihat Laporan Publik
                </a>
            </div>
        @endguest

        @auth
            <h2 class="cta-title">Ada Fasilitas Umum yang Perlu Diperbaiki?</h2>
            <p class="cta-sub">
                Buat laporan sekarang dan bantu pemerintah menindaklanjuti permasalahan di lingkungan Anda.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('lapor.create') }}" class="btn-hero-primary">
                    <i class="bi bi-megaphone-fill"></i>
                    Buat Laporan
                </a>

                <a href="{{ route('rekap') }}" class="btn-hero-outline">
                    <i class="bi bi-search"></i>
                    Lihat Rekap Laporan
                </a>
            </div>
        @endauth

    </div>
</section>

@endsection

@push('scripts')
<script>
    // Simple counter animation for stats
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.stat-number[data-count]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el    = entry.target;
                    const end   = parseInt(el.dataset.count);
                    const dur   = 1400;
                    const step  = dur / end;
                    let current = 0;
                    const timer = setInterval(() => {
                        current += Math.ceil(end / 60);
                        if (current >= end) { current = end; clearInterval(timer); }
                        el.textContent = current.toLocaleString('id-ID');
                    }, step < 16 ? 16 : step);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    });
</script>
@endpush
