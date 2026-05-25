@extends('layout.app')

@section('title', 'Вход')

@section('content')
<div class="row">
    <div class="row--small" style="max-width: 500px; margin: 40px auto;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            @if (session('status'))
                <div class="alert alert-success" style="color: green; margin-bottom: 15px;">
                    {{ session('status') }}
                </div>
            @endif

            <h2>Вход в личный кабинет</h2>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 25px;">
                <button type="submit" class="btn">Войти</button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a>
            </div>
        </form>
    </div>
</div>
@endsection
