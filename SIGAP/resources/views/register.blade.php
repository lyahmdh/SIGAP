@extends('auth')

@section('content')

<h2>Buat Akun</h2>

@if($errors->any())
<div style="color:red;margin-bottom:10px">
    @foreach($errors->all() as $e)
        <div>{{ $e }}</div>
    @endforeach
</div>
@endif

<form method="POST" action="{{ route('register.post') }}">
    @csrf

    <input class="auth-input" name="name" placeholder="Nama"><br><br>
    <input class="auth-input" type="email" name="email" placeholder="Email"><br><br>
    <input class="auth-input" type="password" name="password" placeholder="Password"><br><br>
    <input class="auth-input" name="phone" placeholder="No HP"><br><br>

    <button class="btn-submit">Buat Akun</button>
</form>

<p>
    Sudah punya akun?
    <a href="{{ route('login') }}">Masuk</a>
</p>

@endsection