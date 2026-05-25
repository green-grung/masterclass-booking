<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CabinetController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isMaster()) {
            abort(403);
        }

        $masterClasses = $user->masterClasses()
            ->with('craft', 'registrations')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        return view('cabinet', compact('user', 'masterClasses'));
    }
}
