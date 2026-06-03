@extends('layouts.app')
@section('title', 'Verifikasi Email')

@section('content')
<div style="min-height:60vh; display:flex; align-items:center; justify-content:center; padding:2rem">
    <div style="max-width:480px; width:100%; background:#fff; border-radius:12px;
                border:1px solid #e5e7eb; padding:2.5rem; text-align:center;
                box-shadow:0 2px 12px rgba(0,0,0,.06)">

        <div style="font-size:3rem; margin-bottom:1rem">📧</div>

        <h1 style="font-size:1.4rem; font-weight:700; color:#14532d; margin-bottom:.75rem">
            Verifikasi Email Anda
        </h1>

        <p style="color:#6b7280; font-size:.9rem; line-height:1.6; margin-bottom:1.5rem">
            Kami telah mengirimkan link verifikasi ke email Anda.
            Silakan cek inbox (atau folder spam) dan klik link di dalamnya.
        </p>

        @if(session('status') == 'verification-link-sent')
        <div style="background:#d1fae5; color:#065f46; border-radius:8px;
                    padding:.75rem 1rem; font-size:.85rem; margin-bottom:1.25rem">
            ✅ Email verifikasi berhasil dikirim ulang!
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    style="width:100%; padding:.75rem; background:#14532d; color:#fff;
                           border:none; border-radius:8px; font-weight:700;
                           font-size:.9rem; cursor:pointer">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:.75rem">
            @csrf
            <button type="submit"
                    style="width:100%; padding:.6rem; background:none; color:#6b7280;
                           border:1px solid #e5e7eb; border-radius:8px;
                           font-size:.85rem; cursor:pointer">
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection