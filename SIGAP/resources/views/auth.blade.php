<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="pageTitle">Masuk – SIGAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --green-dark:   #1E3A1F;
        --green-mid:    #2D5A2E;
        --green-light:  #4A7C4E;
        --white:        #FFFFFF;
        --off-white:    #F9F8F5;
        --text-dark:    #111111;
        --text-muted:   #6B7280;
        --border:       #D1D5DB;
        --red:          #EF4444;
        --font-main:    'Plus Jakarta Sans', sans-serif;
        --font-display: 'Lora', serif;
        --r-card:  28px;
        --r-input: 50px;
        --panel-w: 42%;
        --speed:   .55s;
        --ease:    cubic-bezier(.77,0,.18,1);
    }

    body {
        font-family: var(--font-main);
        min-height: 100svh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        background: #1a1a1a;
    }

    .bg-photo {
        position: fixed;
        inset: 0;
        background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600&q=80');
        background-size: cover;
        background-position: center;
        filter: brightness(.52) saturate(.65);
        z-index: 0;
    }

    /* ── Auth card ── */
    .auth-card {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 840px;
        min-height: 500px;
        border-radius: var(--r-card);
        background: var(--off-white);
        box-shadow: 0 32px 90px rgba(0,0,0,.50);
        overflow: hidden;
        display: flex;
    }

    /* ══ FORM PANELS WRAPPER ══
       Dua form (login + register) ditumpuk horizontal di dalam container
       yang sama lebarnya dengan form area. Kita geser translateX untuk switch. */
    .forms-track {
        /* ambil sisa lebar setelah green panel */
        width: calc(100% - var(--panel-w));
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        /* urutan dalam flex card: posisi kiri saat login, kanan saat register
           — diatur via order di JS */
        order: 1;
    }

    .forms-slider {
        display: flex;
        width: 200%;          /* dua form berdampingan */
        height: 100%;
        transition: transform var(--speed) var(--ease);
    }

    /* Default: login tampil (translateX 0), register tersembunyi di kanan */
    .forms-slider { transform: translateX(0); }

    /* Saat mode register: geser ke kiri 50% (= lebar satu form panel) */
    .auth-card.is-register .forms-slider {
        transform: translateX(-50%);
    }

    .form-panel {
        width: 50%;           /* setengah dari forms-slider = satu form */
        flex-shrink: 0;
        padding: 3rem 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--off-white);
    }

    /* ══ GREEN PANEL ══ */
    .green-panel {
        width: var(--panel-w);
        flex-shrink: 0;
        background: linear-gradient(155deg, var(--green-light) 0%, var(--green-dark) 100%);
        border-radius: var(--r-card);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        text-align: center;
        order: 2;
        /* Green panel geser dari kanan ke kiri saat switch ke register */
        transition: order 0s;
        position: relative;
        overflow: hidden;
    }

    /* Konten dalam green panel — dua state: login & register */
    .gp-content {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        text-align: center;
        transition: opacity .25s ease, transform .35s var(--ease);
    }
    .gp-login    { opacity: 1; transform: translateY(0);    pointer-events: auto; }
    .gp-register { opacity: 0; transform: translateY(12px); pointer-events: none; }

    .auth-card.is-register .gp-login    { opacity: 0; transform: translateY(-12px); pointer-events: none; }
    .auth-card.is-register .gp-register { opacity: 1; transform: translateY(0);     pointer-events: auto; }

    /* Green panel slide: login=kanan, register=kiri */
    .auth-card.is-register .green-panel { order: 1; }
    .auth-card.is-register .forms-track { order: 2; }

    /* Animasi geser green panel saat switch */
    .green-panel {
        transition: transform var(--speed) var(--ease);
    }
    /* Saat transisi ke register: green panel dari kanan ke kiri → pakai JS class */
    .auth-card.switching-to-register .green-panel {
        animation: gpSlideLeft var(--speed) var(--ease) forwards;
    }
    .auth-card.switching-to-login .green-panel {
        animation: gpSlideRight var(--speed) var(--ease) forwards;
    }
    @keyframes gpSlideLeft {
        from { transform: translateX(0); }
        to   { transform: translateX(0); } /* order change handles visual, animation just triggers repaint */
    }
    @keyframes gpSlideRight {
        from { transform: translateX(0); }
        to   { transform: translateX(0); }
    }

    /* ── Green panel text ── */
    .green-title {
        font-family: var(--font-display);
        font-size: 1.9rem;
        font-weight: 700;
        color: var(--white);
        line-height: 1.22;
        margin-bottom: 1rem;
    }
    .green-sub {
        font-size: .875rem;
        color: rgba(255,255,255,.78);
        line-height: 1.75;
        max-width: 215px;
        margin-bottom: 1.75rem;
    }
    .btn-green-switch {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.6rem;
        border: 2px solid rgba(255,255,255,.55);
        border-radius: var(--r-input);
        background: transparent;
        color: var(--white);
        font-size: .875rem;
        font-weight: 700;
        font-family: var(--font-main);
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, border-color .2s;
        user-select: none;
    }
    .btn-green-switch:hover {
        background: rgba(255,255,255,.15);
        border-color: var(--white);
        color: var(--white);
    }

    /* ── Form elements ── */
    .auth-title {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .65rem;
        width: 100%;
        padding: .63rem 1.5rem;
        border: 1.5px solid var(--border);
        border-radius: var(--r-input);
        background: var(--white);
        color: var(--text-dark);
        font-size: .875rem;
        font-weight: 600;
        font-family: var(--font-main);
        cursor: not-allowed;
        opacity: .7;
        transition: all .2s;
    }
    .btn-google svg { width: 17px; height: 17px; flex-shrink: 0; }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin: .8rem 0;
        color: var(--text-muted);
        font-size: .78rem;
    }
    .auth-divider::before,
    .auth-divider::after { content:''; flex:1; height:1px; background:#E5E7EB; }

    .input-wrap { position: relative; margin-bottom: .7rem; }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-dark);
        font-size: .9rem;
        z-index: 2;
        pointer-events: none;
    }
    .auth-input {
        width: 100%;
        padding: .65rem 2.8rem .65rem 2.5rem;
        border: 1.5px solid var(--border);
        border-radius: var(--r-input);
        font-size: .875rem;
        font-family: var(--font-main);
        color: var(--text-dark);
        background: var(--white);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .auth-input::placeholder { color: #9CA3AF; }
    .auth-input:focus {
        border-color: var(--green-mid);
        box-shadow: 0 0 0 3px rgba(45,90,46,.11);
    }
    .auth-input.is-invalid { border-color: var(--red); }

    .pw-eye {
        position: absolute;
        right: .9rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: .9rem;
        z-index: 2;
        padding: 0;
        line-height: 1;
    }
    .pw-eye:hover { color: var(--text-dark); }

    .field-err {
        font-size: .72rem;
        color: var(--red);
        padding-left: 1rem;
        margin-top: -.45rem;
        margin-bottom: .45rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    /* Password strength */
    .pw-bars {
        display: flex;
        gap: .3rem;
        padding: 0 .9rem;
        margin-top: -.35rem;
        margin-bottom: .5rem;
    }
    .pw-bar { flex:1; height:3px; border-radius:4px; background:#E5E7EB; transition: background .3s; }
    .pw-bar.weak   { background: #EF4444; }
    .pw-bar.medium { background: #F59E0B; }
    .pw-bar.strong { background: var(--green-mid); }

    .btn-submit {
        width: 100%;
        padding: .72rem;
        background: var(--green-mid);
        border: none;
        border-radius: var(--r-input);
        color: var(--white);
        font-size: .95rem;
        font-weight: 700;
        font-family: var(--font-main);
        cursor: pointer;
        transition: background .2s, transform .1s;
        margin-top: .15rem;
    }
    .btn-submit:hover { background: var(--green-dark); transform: translateY(-1px); }

    .alert-err {
        background: #FFF5F5;
        border: 1px solid #FECACA;
        border-radius: 12px;
        padding: .65rem .9rem;
        font-size: .78rem;
        color: #DC2626;
        margin-bottom: .85rem;
        display: flex;
        align-items: flex-start;
        gap: .45rem;
    }

    .switch-link {
        text-align: center;
        font-size: .82rem;
        color: var(--text-muted);
        margin-top: .9rem;
    }
    .switch-link a {
        color: var(--text-dark);
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }
    .switch-link a:hover { text-decoration: underline; }

    /* ── Register form: sedikit lebih compact ── */
    .form-panel.panel-register .auth-title { margin-bottom: 1rem; }
    .form-panel.panel-register .input-wrap { margin-bottom: .6rem; }
    .form-panel.panel-register .auth-divider { margin: .6rem 0; }

    /* ── Mobile ── */
    @media (max-width: 600px) {
        .auth-card {
            flex-direction: column;
            min-height: auto;
        }
        .forms-track {
            width: 100%;
            order: 1 !important;
        }
        .green-panel {
            width: 100%;
            order: 2 !important;
            border-radius: 0 0 var(--r-card) var(--r-card);
            padding: 1.5rem;
            min-height: 140px;
        }
        .form-panel {
            padding: 2rem 1.5rem;
        }
        .btn-green-switch { display: none; }
        .gp-content { position: static; padding: 0; }
        .gp-register { display: none; }
        .auth-card.is-register .gp-login    { display: none; opacity: 1; }
        .auth-card.is-register .gp-register { display: flex; opacity: 1; transform: none; }
    }
    </style>
</head>
<body>
<div class="bg-photo"></div>

<div class="auth-card" id="authCard">

    {{-- ══ FORMS TRACK ══ --}}
    <div class="forms-track">
        <div class="forms-slider">

            {{-- ── LOGIN FORM ── --}}
            <div class="form-panel panel-login">
                <h1 class="auth-title">Masuk</h1>

                @if($errors->any() && session('_auth_mode', 'login') === 'login')
                <div class="alert-err">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" novalidate>
                    @csrf

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="email"
                               class="auth-input @error('email') is-invalid @enderror"
                               placeholder="Email"
                               value="{{ old('email') }}"
                               autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="loginPw"
                               class="auth-input @error('password') is-invalid @enderror"
                               placeholder="Kata Sandi"
                               autocomplete="current-password" required>
                        <button type="button" class="pw-eye" onclick="togglePw('loginPw','loginPwIco')">
                            <i class="bi bi-eye-slash" id="loginPwIco"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-submit">Masuk</button>
                </form>

                <p class="switch-link">
                    Belum punya akun?
                    <a onclick="switchToRegister()">Buat Akun</a>
                </p>
            </div>

            {{-- ── REGISTER FORM ── --}}
            <div class="form-panel panel-register">
                <h1 class="auth-title">Buat Akun</h1>

                @if($errors->any() && session('_auth_mode', 'login') === 'register')
                <div class="alert-err">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" novalidate>
                    @csrf

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="name"
                               class="auth-input @error('name') is-invalid @enderror"
                               placeholder="Nama lengkap"
                               value="{{ old('name') }}"
                               autocomplete="name" required>
                    </div>
                    @error('name')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="email"
                               class="auth-input @error('email') is-invalid @enderror"
                               placeholder="Email"
                               value="{{ old('email') }}"
                               autocomplete="email" required>
                    </div>
                    @error('email')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="regPw"
                               class="auth-input @error('password') is-invalid @enderror"
                               placeholder="Kata Sandi"
                               autocomplete="new-password"
                               oninput="pwStrength(this.value)"
                               required minlength="8">
                        <button type="button" class="pw-eye" onclick="togglePw('regPw','regPwIco')">
                            <i class="bi bi-eye-slash" id="regPwIco"></i>
                        </button>
                    </div>
                    <div class="pw-bars">
                        <div class="pw-bar" id="pb1"></div>
                        <div class="pw-bar" id="pb2"></div>
                        <div class="pw-bar" id="pb3"></div>
                    </div>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </span>

                        <input type="password"
                            name="password_confirmation"
                            id="regPwConfirm"
                            class="auth-input"
                            placeholder="Konfirmasi Kata Sandi"
                            autocomplete="new-password">

                        <button type="button"
                                class="pw-eye"
                                onclick="togglePw('regPwConfirm','regPwConfirmIco')">
                            <i class="bi bi-eye-slash" id="regPwConfirmIco"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" name="phone"
                               class="auth-input @error('phone') is-invalid @enderror"
                               placeholder="No. Telepon (opsional)"
                               value="{{ old('phone') }}"
                               autocomplete="tel">
                    </div>
                    @error('phone')
                        <div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-submit">Buat Akun</button>
                </form>

                <p class="switch-link">
                    Sudah punya akun?
                    <a onclick="switchToLogin()">Masuk</a>
                </p>
            </div>

        </div>{{-- /forms-slider --}}
    </div>{{-- /forms-track --}}

    {{-- ══ GREEN PANEL ══ --}}
    <div class="green-panel">

        {{-- State: Login --}}
        <div class="gp-content gp-login">
            <div class="green-title">Selamat Datang<br>Kembali!</div>
            <p class="green-sub">Bangun layanan publik yang lebih baik bersama kami. Laporkan, pantau, dan wujudkan perubahan nyata.</p>
            <button class="btn-green-switch" onclick="switchToRegister()">
                <i class="bi bi-person-plus"></i> Buat Akun
            </button>
        </div>

        {{-- State: Register --}}
        <div class="gp-content gp-register">
            <div class="green-title">Sudah<br>Punya Akun?</div>
            <p class="green-sub">Masuk dan lanjutkan memantau laporan Anda. Bersama kita wujudkan perubahan nyata.</p>
            <button class="btn-green-switch" onclick="switchToLogin()">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>
        </div>

    </div>{{-- /green-panel --}}

</div>{{-- /auth-card --}}

<script>
const card = document.getElementById('authCard');

// Prioritas: session (saat validasi gagal) > mode dari controller > default login
const serverMode = '{{ session("_auth_mode") ?: ($mode ?? "login") }}';

if (serverMode === 'register') {
    card.classList.add('is-register');
}

// ── Switch ke Register
function switchToRegister() {
    if (card.classList.contains('is-register')) return;
    card.classList.add('is-register');
    document.getElementById('pageTitle').textContent = 'Buat Akun – SIGAP';
    // Update URL tanpa reload (opsional, untuk UX back-button)
    history.replaceState(null, '', '/register');
}

// ── Switch ke Login
function switchToLogin() {
    if (!card.classList.contains('is-register')) return;
    card.classList.remove('is-register');
    document.getElementById('pageTitle').textContent = 'Masuk – SIGAP';
    history.replaceState(null, '', '/login');
}

// ── Toggle password visibility
function togglePw(inputId, iconId) {
    const input  = document.getElementById(inputId);
    const icon   = document.getElementById(iconId);
    const hidden = input.type === 'password';
    input.type   = hidden ? 'text' : 'password';
    icon.className = hidden ? 'bi bi-eye' : 'bi bi-eye-slash';
}

// ── Password strength indicator
function pwStrength(val) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/\d/.test(val) || /[^a-zA-Z0-9]/.test(val)) score++;
    const cls = ['', 'weak', 'medium', 'strong'];
    ['pb1','pb2','pb3'].forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'pw-bar';
        if (val.length > 0 && i < score) el.classList.add(cls[score]);
    });
}
</script>
</body>
</html>