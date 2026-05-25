@extends('layout.app')

@section('title', 'Главная')

@section('content')
<div class="row">
    <div class="hover"></div>
    <div class="title"></div>
    <div class="row--small grid between">
        <div class="content">
            <p>Добро пожаловать в клуб "ОчУмелые ручки"! Мы предлагаем разнообразные мастер-классы по рукоделию, творчеству и кулинарии.</p>
            <p>Наш адрес: ВДНХ, 120в. Тел: 89123456765</p>
            <p>Выберите интересующий вас вид творчества в меню справа.</p>
        </div>
        <ul class="menu">
            @foreach($crafts as $craft)
                <li><a href="{{ route('craft.show', $craft) }}">{{ $craft->name }}</a></li>
            @endforeach
        </ul>
    </div>

    @auth
        @if(isset($myRegistrations) && $myRegistrations->count())
            <div class="row--small" style="margin-top: 30px;">
                <h3>Мои записи на мастер-классы</h3>
                @foreach($myRegistrations as $reg)
                    <div class="driver grid" style="background:#f5f5f5; margin-bottom:10px; padding:10px;">
                        <div class="driver-left">
                            <div class="driver-name">{{ $reg->masterClass->title }}</div>
                            <div class="driver-desc">
                                {{ $reg->masterClass->craft->name }},
                                ведущий: {{ $reg->masterClass->master->name }},
                                дата: {{ $reg->masterClass->date->format('d.m.Y') }},
                                время: {{ $reg->masterClass->time_slot }}ч
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row--small" style="margin-top: 30px;">
                <p>У вас пока нет записей на мастер-классы. <a href="{{ route('home') }}">Выберите интересующий вид творчества</a> и запишитесь!</p>
            </div>
        @endif
    @endauth
</div>
@endsection