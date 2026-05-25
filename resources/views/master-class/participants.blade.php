@extends('layout.app')

@section('title', 'Участники мастер-класса')

@section('content')
<div class="row">
    <div class="row--small">
        <h2>Мастер-класс: {{ $masterClass->title }}</h2>
        <p><strong>Дата:</strong> {{ $masterClass->date->format('d.m.Y') }} {{ $masterClass->time_slot }}</p>
        <p><strong>Всего мест:</strong> {{ $masterClass->max_participants }}</p>
        <p><strong>Записано:</strong> {{ $participants->count() }}</p>

        <h3>Список участников</h3>
        <ul>
            @forelse($participants as $reg)
                <li>{{ $reg->user->name }} ({{ $reg->user->email }}, тел: {{ $reg->user->phone }})</li>
            @empty
                <li>Нет участников.</li>
            @endforelse
        </ul>
        <a href="{{ route('cabinet') }}" class="btn">Назад в кабинет</a>
    </div>
</div>
@endsection