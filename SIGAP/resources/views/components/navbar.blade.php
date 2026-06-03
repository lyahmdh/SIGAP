{{--
    Navbar Component
    Variables expected (from controller / view composers):
      $authRole  – 'guest' | 'user' | 'admin'
      $authUser  – Auth::user() object (nullable for guest)
      $currentRoute – current route name (optional, for active state)
--}}

@php
    // Fallback defaults so the component never crashes during prototyping
    $authRole    = $authRole    ?? 'guest';   // 'guest' | 'user' | 'admin'
    $authUser    = $authUser    ?? null;
    $currentRoute = $currentRoute ?? request()->route()?->getName() ?? '';

    // Helper: first letter(s) for avatar initials
    $initials = '';
    if (Auth::check()) {
        $parts = explode(' ', trim(Auth::user()->name));
        $initials = strtoupper(substr($parts[0], 0, 1));

        if (count($parts) > 1) {
            $initials .= strtoupper(substr(end($parts), 0, 1));
        }
    }

    // Active helper
    $isActive = function(array $routes) use ($currentRoute) {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    };
@endphp

<nav class="navbar navbar-expand-lg sigap-navbar">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand" href="{{ route('home') ?? '/' }}">
            <span>SI</span>GAP
        </a>

        {{-- Mobile toggler --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#sigapNav"
                aria-controls="sigapNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Nav links --}}
        <div class="collapse navbar-collapse" id="sigapNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3 gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ $isActive(['home']) }}"
                    href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $isActive(['lapor.create','lapor.store']) }}"
                    href="{{ route('lapor.create') }}">
                        Lapor
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $isActive(['rekap']) }}"
                    href="{{ route('rekap') }}">
                        Rekap
                    </a>
                </li>
            </ul>

            {{-- Right side: auth buttons or avatar --}}
            <div class="d-flex align-items-center gap-2">

                @guest
                    {{-- GUEST – show Login & Daftar --}}
                    <a href="{{ route('login') ?? '/login' }}" class="nav-link btn-nav-outline">
                        Masuk
                    </a>
                    <a href="{{ route('register') ?? '/register' }}" class="nav-link btn-nav-solid">
                        Daftar
                    </a>
                @endguest

                @auth
                    {{-- USER / ADMIN – show avatar with dropdown --}}
                    <div class="dropdown">
                        <button class="d-flex align-items-center gap-2 bg-transparent border-0 p-0"
                                type="button"
                                id="avatarDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                            @if(!empty(Auth::user()?->profile_photo))
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                     alt="{{ Auth::user()->name }}"
                                     class="avatar-btn">
                            @else
                                <div class="avatar-initials">{{ $initials }}</div>
                            @endif

                            <span class="text-white fw-500 d-none d-lg-inline" style="font-size:.875rem; max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ Auth::user()->name }}
                            </span>
                            <i class="bi bi-chevron-down text-white" style="font-size:.7rem; opacity:.7;"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-user mt-2"
                            aria-labelledby="avatarDropdown">

                            {{-- User info header --}}
                            <li class="px-3 py-2">
                                <div class="fw-700" style="font-size:.875rem; color:var(--text-dark);">
                                    {{ Auth::user()->name }}
                                </div>
                                <div style="font-size:.775rem; color:var(--text-muted);">
                                    {{ Auth::user()->email }}
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            @if(Auth::user()->role === 'admin')

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2" style="color:var(--brown-accent);"></i>
                                        Dashboard Admin
                                    </a>
                                </li>

                            @else

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                    href="{{ route('profile') }}">
                                        <i class="bi bi-person-circle" style="color:var(--green-mid);"></i>
                                        Profil Saya
                                    </a>
                                </li>

                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <!-- <form method="POST" action="{{ route('logout') ?? '/logout' }}">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 dropdown-item-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Keluar
                                    </button>
                                </form> -->
                                <form method="POST" action="{{ route('logout') }}"
                                    onsubmit="
                                        Object.keys(sessionStorage)
                                        .filter(k => k.startsWith('emailBannerDismissed_'))
                                        .forEach(k => sessionStorage.removeItem(k));
                                    ">
                                    @csrf
                                    <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 dropdown-item-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                
                @endauth
            </div>{{-- /right side --}}
        </div>{{-- /collapse --}}
    </div>{{-- /container --}}
</nav>
