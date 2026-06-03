<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIGAP – {{ $title ?? 'Sistem Informasi Gangguan & Aduan Publik' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,600;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --green-dark:   #1E3A1F;
            --green-mid:    #2D5A2E;
            --green-light:  #4A7C4E;
            --green-pale:   #C8DEC9;
            --brown-dark:   #3D1C0B;
            --brown-mid:    #7B3310;
            --brown-warm:   #C05C1A;
            --brown-accent: #E07B3A;
            --cream:        #F5F0E8;
            --cream-dark:   #EDE6D6;
            --white:        #FFFFFF;
            --text-dark:    #1A1A1A;
            --text-muted:   #6B7280;
            --font-main:    'Plus Jakarta Sans', sans-serif;
            --font-display: 'Lora', serif;
            --radius-sm:    8px;
            --radius-md:    14px;
            --radius-lg:    22px;
            --shadow-sm:    0 2px 8px rgba(0,0,0,.07);
            --shadow-md:    0 6px 24px rgba(0,0,0,.10);
            --shadow-lg:    0 16px 48px rgba(0,0,0,.14);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-main);
            background-color: var(--cream);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main { flex: 1; }

        /* ─── NAVBAR ──────────────────────────────────── */
        .sigap-navbar {
            background-color: var(--green-dark);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 12px rgba(0,0,0,.25);
        }

        .sigap-navbar .navbar-brand {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--white) !important;
            letter-spacing: .5px;
        }

        .sigap-navbar .navbar-brand span {
            color: var(--brown-accent);
        }

        .sigap-navbar .nav-link {
            color: rgba(255,255,255,.80) !important;
            font-weight: 500;
            font-size: .925rem;
            padding: .4rem .9rem !important;
            border-radius: var(--radius-sm);
            transition: all .2s;
        }

        .sigap-navbar .nav-link:hover,
        .sigap-navbar .nav-link.active {
            color: var(--white) !important;
            background-color: rgba(255,255,255,.12);
        }

        /* Auth buttons – guest */
        .btn-nav-outline {
            border: 1.5px solid rgba(255,255,255,.50);
            color: var(--white) !important;
            border-radius: 50px;
            padding: .38rem 1.1rem !important;
            font-size: .875rem;
            font-weight: 600;
            transition: all .2s;
        }
        .btn-nav-outline:hover {
            border-color: var(--white);
            background: rgba(255,255,255,.12);
        }

        .btn-nav-solid {
            background-color: var(--brown-accent);
            border: 1.5px solid var(--brown-accent);
            color: var(--white) !important;
            border-radius: 50px;
            padding: .38rem 1.25rem !important;
            font-size: .875rem;
            font-weight: 700;
            transition: all .2s;
        }
        .btn-nav-solid:hover {
            background-color: var(--brown-warm);
            border-color: var(--brown-warm);
        }

        /* Avatar dropdown – user */
        .avatar-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--brown-accent);
            cursor: pointer;
            transition: border-color .2s, transform .2s;
            padding: 0;
            background: none;
        }
        .avatar-btn:hover { border-color: var(--white); transform: scale(1.05); }
        .avatar-btn img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-initials {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--brown-accent);
            color: var(--white);
            font-weight: 700;
            font-size: .875rem;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--brown-accent);
            transition: transform .2s;
        }
        .avatar-initials:hover { transform: scale(1.05); }

        .dropdown-menu-user {
            border: 1px solid var(--cream-dark);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            padding: .5rem;
            min-width: 180px;
        }
        .dropdown-menu-user .dropdown-item {
            border-radius: var(--radius-sm);
            font-size: .9rem;
            padding: .5rem .85rem;
            font-weight: 500;
        }
        .dropdown-menu-user .dropdown-item:hover { background: var(--cream); }
        .dropdown-menu-user .dropdown-divider { margin: .35rem 0; }
        .dropdown-item-danger { color: #DC3545 !important; }
        .dropdown-item-danger:hover { background: #FFF0F0 !important; }

        /* Mobile toggler */
        .navbar-toggler { border: 1.5px solid rgba(255,255,255,.4); }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ─── FOOTER ──────────────────────────────────── */
        .sigap-footer {
            background-color: var(--green-dark);
            color: rgba(255,255,255,.75);
            padding: 3rem 0 1.5rem;
            font-size: .9rem;
        }

        .sigap-footer .footer-logo {
            font-family: var(--font-display);
            font-size: 1.4rem;
            color: var(--white);
            font-weight: 600;
        }
        .sigap-footer .footer-logo span { color: var(--brown-accent); }

        .sigap-footer .footer-tagline {
            font-size: .8rem;
            color: rgba(255,255,255,.50);
            margin-top: .25rem;
        }

        .sigap-footer .footer-heading {
            color: var(--white);
            font-weight: 700;
            font-size: .875rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 1rem;
        }

        .sigap-footer .footer-link {
            display: block;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            margin-bottom: .45rem;
            transition: color .2s;
            font-size: .875rem;
        }
        .sigap-footer .footer-link:hover { color: var(--brown-accent); }

        .sigap-footer .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,.10);
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: 1rem;
            transition: all .2s;
            margin-right: .4rem;
        }
        .sigap-footer .social-btn:hover {
            background: var(--brown-accent);
            color: var(--white);
        }

        .sigap-footer .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.12);
            margin-top: 2rem;
            padding-top: 1.25rem;
            font-size: .8rem;
            color: rgba(255,255,255,.40);
        }

        /* ─── GLOBAL UTILITIES ────────────────────────── */
        .badge-status {
            padding: .35em .75em;
            border-radius: 50px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-masuk       { background:#FEF3C7; color:#92400E; }
        .badge-diverifikasi{ background:#D1FAE5; color:#065F46; }
        .badge-diproses    { background:#DBEAFE; color:#1E40AF; }
        .badge-selesai     { background:#D1FAE5; color:#065F46; }
        .badge-ditolak     { background:#FEE2E2; color:#991B1B; }
        .badge-diverifikasi-alt{ background:#EDE9FE; color:#5B21B6; }

        .btn-sigap {
            background-color: var(--green-mid);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            padding: .6rem 1.4rem;
            transition: background .2s, transform .1s;
        }
        .btn-sigap:hover { background-color: var(--green-light); color: var(--white); transform: translateY(-1px); }

        .btn-sigap-brown {
            background-color: var(--brown-accent);
            color: var(--white);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            padding: .6rem 1.4rem;
            transition: background .2s, transform .1s;
        }
        .btn-sigap-brown:hover { background-color: var(--brown-warm); color: var(--white); transform: translateY(-1px); }

        @media (max-width: 768px) {
            .sigap-navbar .d-flex.gap-2 { margin-top: .75rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ─── NAVBAR ─────────────────────────────────────────── --}}
    @include('components.navbar')

    {{-- ─── MAIN CONTENT ───────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ─── FOOTER ─────────────────────────────────────────── --}}
    @include('components.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
