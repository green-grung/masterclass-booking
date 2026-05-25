<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MasterClass;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function confirm(MasterClass $masterClass)
    {
        if ($masterClass->isUserRegistered(Auth::id())) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        }

        if ($masterClass->availablePlaces() <= 0) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Нет свободных мест.');
        }

        $user = Auth::user();

        return view('registration.confirm', [
            'masterClass' => $masterClass,
            'user' => $user,
        ]);
    }

    public function store(Request $request, MasterClass $masterClass)
    {
        $action = $request->input('action');

        if ($action === 'cancel') {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('info', 'Запись отменена.');
        }

        if ($masterClass->isUserRegistered(Auth::id())) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Вы уже записаны.');
        }

        if ($masterClass->availablePlaces() <= 0) {
            return redirect()->route('craft.show', $masterClass->craft_id)
                ->with('error', 'Нет свободных мест.');
        }

        Registration::create([
            'user_id' => Auth::id(),
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed',
        ]);

        return redirect()->route('craft.show', $masterClass->craft_id)
            ->with('success', 'Вы успешно записаны на мастер-класс!');
    }
}