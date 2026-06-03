@extends('auth')

@section('content')

<h2>Masuk</h2>

@if($errors->any())
<div style="color:red;margin-bottom:10px">
    @foreach($errors->all() as $e)
        <div>{{ $e }}</div>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('login.post') }}">
    @csrf

    <input class="auth-input" type="email" name="email" placeholder="Email"><br><br>
    <input class="auth-input" type="password" name="password" placeholder="Password"><br><br>

    <button class="btn-submit">Masuk</button>
</form>

<p>
    Belum punya akun?
    <a href="{{ route('register') }}">Daftar</a>
</p>

@endsection