<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun – SIGAP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
    /* ── View Transition: kotak hijau geser ── */
    @view-transition { navigation: auto; }

    ::view-transition-old(green-panel),
    ::view-transition-new(green-panel) {
        animation-duration: .65s;
        animation-timing-function: cubic-bezier(.77,0,.18,1);
    }

    /* Register → Login: hijau geser ke kanan */
    ::view-transition-old(green-panel) {
        animation-name: slideOutLeft;
    }
    ::view-transition-new(green-panel) {
        animation-name: slideInRight;
    }

    @keyframes slideOutLeft {
        from { transform: translateX(0);     opacity: 1; }
        to   { transform: translateX(-100%); opacity: 0; }
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to   { transform: translateX(0);    opacity: 1; }
    }

    /* Form panel fade */
    ::view-transition-old(form-panel),
    ::view-transition-new(form-panel) {
        animation-duration: .45s;
        animation-timing-function: ease;
    }
    ::view-transition-old(form-panel) { animation-name: fadeOut; }
    ::view-transition-new(form-panel) { animation-name: fadeIn; animation-delay: .2s; }
    @keyframes fadeOut { from { opacity:1; } to { opacity:0; } }
    @keyframes fadeIn  { from { opacity:0; } to { opacity:1; } }

    /* ── Reset & base ── */
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

    /* ── Green panel (kiri untuk register) ── */
    .green-panel {
        view-transition-name: green-panel;
        width: 42%;
        flex-shrink: 0;
        background: linear-gradient(155deg, var(--green-light) 0%, var(--green-dark) 100%);
        border-radius: var(--r-card);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        text-align: center;
        order: 1;
    }

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
    }
    .btn-green-switch:hover {
        background: rgba(255,255,255,.15);
        border-color: var(--white);
        color: var(--white);
    }

    /* ── Form panel (kanan) ── */
    .form-panel {
        view-transition-name: form-panel;
        flex: 1;
        padding: 2.5rem 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: var(--off-white);
        min-width: 0;
        order: 2;
    }

    /* ── Form elements ── */
    .auth-title {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 1.2rem;
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
        cursor: pointer;
        transition: all .2s;
    }
    .btn-google:hover { border-color: #9CA3AF; background: #F9FAFB; }
    .btn-google svg { width: 17px; height: 17px; flex-shrink: 0; }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin: .7rem 0;
        color: var(--text-muted);
        font-size: .78rem;
    }
    .auth-divider::before,
    .auth-divider::after { content:''; flex:1; height:1px; background:#E5E7EB; }

    .input-wrap { position: relative; margin-bottom: .65rem; }
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
        padding: .62rem 2.8rem .62rem 2.5rem;
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
        margin-top: -.35rem;
        margin-bottom: .35rem;
        display: flex;
        align-items: center;
        gap: .3rem;
    }

    /* Password strength */
    .pw-bars {
        display: flex;
        gap: .3rem;
        padding: 0 .9rem;
        margin-top: -.3rem;
        margin-bottom: .5rem;
    }
    .pw-bar {
        flex: 1; height: 3px;
        border-radius: 4px;
        background: #E5E7EB;
        transition: background .3s;
    }
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
        margin-top: .1rem;
    }
    .btn-submit:hover { background: var(--green-dark); transform: translateY(-1px); }

    .alert-err {
        background: #FFF5F5;
        border: 1px solid #FECACA;
        border-radius: 12px;
        padding: .65rem .9rem;
        font-size: .78rem;
        color: #DC2626;
        margin-bottom: .8rem;
        display: flex;
        align-items: flex-start;
        gap: .45rem;
    }

    /* mobile */
    @media (max-width: 600px) {
        .auth-card { flex-direction: column; min-height: auto; }
        .green-panel { width: 100%; order: 2; border-radius: 0 0 var(--r-card) var(--r-card); padding: 1.75rem; }
        .form-panel { order: 1; padding: 2rem 1.5rem; }
        .btn-green-switch { display: none; }
    }
    </style>
</head>
<body>
<div class="bg-photo"></div>

<div class="auth-card">

    <!-- ── GREEN PANEL (kiri) ── -->
    <div class="green-panel">
        <div class="green-title">Selamat<br>Datang!</div>
        <p class="green-sub">Bangun layanan publik yang lebih baik bersama kami. Laporkan, pantau, dan wujudkan perubahan nyata.</p>
        <a href="{{ route('login') }}" class="btn-green-switch">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </a>
    </div>

    <!-- ── FORM PANEL (kanan) ── -->
    <div class="form-panel">
        <h1 class="auth-title">Buat Akun</h1>

        @if($errors->any())
        <div class="alert-err">
            <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"></i>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <button class="btn-google" type="button" disabled>
            <svg viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google
        </button>

        <div class="auth-divider">Atau</div>

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
            @error('name')<div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror

            <div class="input-wrap">
                <span class="input-icon"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email"
                       class="auth-input @error('email') is-invalid @enderror"
                       placeholder="Email"
                       value="{{ old('email') }}"
                       autocomplete="email" required>
            </div>
            @error('email')<div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror

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
            @error('password')<div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror

            <div class="input-wrap">
                <span class="input-icon"><i class="bi bi-telephone-fill"></i></span>
                <input type="tel" name="phone"
                       class="auth-input @error('phone') is-invalid @enderror"
                       placeholder="No.Telepon"
                       value="{{ old('phone') }}"
                       autocomplete="tel">
            </div>
            @error('phone')<div class="field-err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror

            <button type="submit" class="btn-submit">Buat Akun</button>
        </form>

        <p style="text-align:center;font-size:.82rem;color:var(--text-muted);margin-top:.85rem">
            Sudah punya akun?
            <a href="{{ route('login') }}"
               style="color:var(--text-dark);font-weight:700;text-decoration:none">
               Masuk
            </a>
        </p>
    </div>

</div>

<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const hidden = input.type === 'password';
    input.type = hidden ? 'text' : 'password';
    icon.className = hidden ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function pwStrength(val) {
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/\d/.test(val) || /[^a-zA-Z0-9]/.test(val)) score++;
    const cls = ['','weak','medium','strong'];
    ['pb1','pb2','pb3'].forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'pw-bar';
        if (val.length > 0 && i < score) el.classList.add(cls[score]);
    });
}
</script>
</body>
</html>
