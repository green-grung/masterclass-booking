<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Craft;
use Illuminate\Support\Facades\Auth;

class CraftController extends Controller
{
    public function show(Craft $craft)
    {
        $masterClasses = $craft->masterClasses()
            ->with('master')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        $isAuthenticated = Auth::check();

        return view('craft.show', compact('craft', 'masterClasses', 'isAuthenticated'));
    }
}