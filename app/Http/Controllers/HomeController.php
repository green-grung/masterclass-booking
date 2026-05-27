<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Craft;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $crafts = Craft::all();
        $myRegistrations = collect();

        /** @var User|null $user */
        $user = Auth::user();
        if ($user) {
            $myRegistrations = $user->registrations()
                ->with('masterClass.craft', 'masterClass.master')
                ->where('status', 'confirmed')
                ->get();
        }

        return view('home', compact('crafts', 'myRegistrations'));
    }
}
