@extends('layout.app')

@section('title', 'Личный кабинет ведущего')

@section('content')
<div class="row">
    <div class="row--small grid between">
        <div class="content driver-page">
            <div class="driver-page-photo">
                <img src="{{ $user->photo ? asset('storage/'.$user->photo) : asset('img/driver-page.png') }}">
            </div>
            <div class="driver-page-name">{{ $user->name }}</div>
            <div class="driver-page-text">
                <div class="driver-page-my">Мои мастер-классы</div>
                <table class="driver-page-table">
                    <tbody>
                        @foreach($masterClasses as $mc)
                        <tr>
                            <td>{{ $mc->date->format('d.m.Y') }} {{ $mc->time_slot }}</td>
                            <td>
                                <b>{{ $mc->title }}</b>
                                <p>
                                    <a href="{{ route('master-class.participants', $mc) }}">Список участников</a> |
                                    <a href="{{ route('master-class.edit', $mc) }}">Редактировать</a>
                                </p>
                                @foreach($mc->registrations->where('status','confirmed') as $reg)
                                    {{ $reg->user->name }} ({{ $reg->user->email }})<br>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="driver-page-btn-wrapper">
                <a href="{{ route('master-class.create') }}" class="driver-page-btn btn">Добавить мастер-класс</a>
            </div>
        </div>
        <ul class="menu">
            @foreach(\App\Models\Craft::all() as $c)
                <li><a href="{{ route('craft.show', $c) }}">{{ $c->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@endsection