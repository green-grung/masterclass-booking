@extends('layout.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="row">
    <div class="row--small">
        <h2>Подтверждение записи на мастер-класс</h2>
        <p>ФИО: {{ $user->name }}</p>
        <p>Вид творчества: {{ $masterClass->craft->name }}</p>
        <p>ФИО мастера: {{ $masterClass->master->name }}</p>
        <p>Дата: {{ $masterClass->date->format('d.m.Y') }}</p>
        <p>Время: {{ $masterClass->time_slot }}</p>

        <form method="POST" action="{{ route('registration.store', $masterClass) }}">
            @csrf
            <button type="submit" name="action" value="confirm" class="btn">Подтвердить</button>
            <button type="submit" name="action" value="cancel" class="btn">Отмена</button>
        </form>
    </div>
</div>
@endsection