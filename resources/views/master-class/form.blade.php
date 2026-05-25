@extends('layout.app')

@section('title', $masterClass ? 'Редактирование мастер-класса' : 'Новый мастер-класс')

@section('content')
<div class="row">
    <div class="row--small">
        <form method="POST" action="{{ $masterClass ? route('master-class.update', $masterClass) : route('master-class.store') }}">
            @csrf
            @if($masterClass) @method('PUT') @endif

            <h2>{{ $masterClass ? 'Редактирование мастер-класса' : 'Форма добавления мастер-класса' }}</h2>

            @if(!$masterClass)
            <div class="form-group">
                <label>Вид творчества</label>
                <select name="craft_id" required>
                    <option value="">Выберите...</option>
                    @foreach($crafts as $craft)
                    <option value="{{ $craft->id }}" {{ old('craft_id', $masterClass->craft_id ?? '') == $craft->id ? 'selected' : '' }}>{{ $craft->name }}</option>
                    @endforeach
                </select>
                @error('craft_id') <small style="color:red;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Название мастер-класса</label>
                <input type="text" name="title" value="{{ old('title', $masterClass->title ?? '') }}" required>
                @error('title') <small style="color:red;">{{ $message }}</small> @enderror
            </div>
            @endif

            <div class="form-group">
                <label>Описание мастер-класса</label>
                <textarea name="description" required>{{ old('description', $masterClass->description ?? '') }}</textarea>
                @error('description') <small style="color:red;">{{ $message }}</small> @enderror
            </div>

            @if(!$masterClass)
            <div class="form-group">
                <label>Дата</label>
                <input type="date" name="date" value="{{ old('date') }}" min="{{ date('Y-m-d') }}" required>
                @error('date') <small style="color:red;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Время</label>
                <select name="time_slot" required>
                    <option value="">Выберите...</option>
                    @foreach($timeSlots as $value => $label)
                    @php
                    $selectedDate = old('date', $masterClass->date ?? '');
                    $dateKey = is_string($selectedDate) ? $selectedDate : (is_object($selectedDate) ? $selectedDate->format('Y-m-d') : '');
                    @endphp

                    <option value="{{ $value }}" {{ old('time_slot') == $value ? 'selected' : '' }} {{ isset($occupiedSlots[$dateKey]) && in_array($value, $occupiedSlots[$dateKey]) ? 'disabled' : '' }}>
                        {{ $label }} @if(isset($occupiedSlots[$dateKey]) && in_array($value, $occupiedSlots[$dateKey])) (занято) @endif
                    </option>
                    @endforeach
                </select>
                @error('time_slot') <small style="color:red;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Количество человек в группе</label>
                <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1" required>
                @error('max_participants') <small style="color:red;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label>Стоимость (руб.)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" min="0" required>
                @error('price') <small style="color:red;">{{ $message }}</small> @enderror
            </div>
            @else
            <div class="form-group">
                <label>Стоимость (руб.)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $masterClass->price) }}" min="0" required>
                @error('price') <small style="color:red;">{{ $message }}</small> @enderror
            </div>
            @endif

            <div class="form-group">
                <button class="btn">Сохранить</button>
            </div>
        </form>
    </div>
</div>
@endsection