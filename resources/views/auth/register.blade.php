@extends('layout.app')

@section('title', 'Регистрация')

@section('content')
<div class="row">
    <div class="row--small" style="max-width: 500px; margin: 40px auto;">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <h2>Регистрация нового пользователя</h2>

            <div class="form-group">
                <label for="name">ФИО</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Номер телефона (без пробелов, только цифры)</label>
                <input type="tel" id="phone" name="phone" maxlength="11" value="{{ old('phone') }}" required>
                @error('phone')
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

            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <small style="color: red;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 25px;">
                <button type="submit" class="btn">Зарегистрироваться</button>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}">Уже зарегистрированы? Войти</a>
            </div>
        </form>
    </div>
</div>
@endsection
