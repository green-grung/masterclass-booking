@extends('layout.app')

@section('title', $craft->name)

@section('content')
<div class="row">
    <div class="hover"></div>
    <div class="title">{{ $craft->name }}</div>
    <div class="row--small grid between">
        <div class="content">
            <img src="{{ asset('img/elifant.png') }}" alt="{{ $craft->name }}">
            <p>{!! nl2br(e($craft->description)) !!}</p>
        </div>
        <ul class="menu">
            @foreach(\App\Models\Craft::all() as $c)
                <li><a href="{{ route('craft.show', $c) }}">{{ $c->name }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="row shedule">
        <div class="row--small">
            <h2>Расписание</h2>
            <div class="drivers">
                @forelse($masterClasses as $mc)
                    <div class="driver grid">
                        <div class="driver-left grid">
                            <div class="driver-photo">
                                <img src="{{ $mc->master->photo ? asset('storage/'.$mc->master->photo) : asset('img/driver1.png') }}" style="width:80px; border-radius:50%;">
                            </div>
                            <div class="driver-text">
                                <div class="driver-name">{{ $mc->master->name }}</div>
                                <div class="driver-desc">{{ $mc->description }}</div>
                                <div>Стоимость: {{ $mc->price }} руб.</div>
                                <div>Мест: {{ $mc->availablePlaces() }} / {{ $mc->max_participants }}</div>
                            </div>
                        </div>
                        <div class="driver-right">
                            @auth
                                @if(!$mc->isUserRegistered(Auth::id()) && $mc->availablePlaces() > 0)
                                    <a href="{{ route('registration.confirm', $mc) }}" class="driver-btn">записаться</a>
                                @elseif($mc->isUserRegistered(Auth::id()))
                                    <span class="driver-btn" style="background:#ccc; cursor:default;">Вы записаны</span>
                                @else
                                    <span class="driver-btn" style="background:#ccc; cursor:default;">Нет мест</span>
                                @endif
                            @else
                                <span class="driver-btn" style="background:#ccc; cursor:default;">Войдите для записи</span>
                            @endauth
                            <div class="driver-time">{{ \Carbon\Carbon::parse($mc->date)->format('d.m.Y') }} {{ $mc->time_slot }}</div>
                        </div>
                    </div>
                @empty
                    <p style="color:white;">Расписание пока пусто.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection