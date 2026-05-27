<?php

use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CraftController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MasterClassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/craft/{craft}', [CraftController::class, 'show'])->name('craft.show');

// регистрация и авторизация (Breeze)
require __DIR__.'/auth.php';

// защищ маршруты
Route::middleware(['auth'])->group(function () {
    Route::get('/cabinet', [CabinetController::class, 'index'])->name('cabinet')
        ->middleware('role:master');
    Route::get('/master-class/create', [MasterClassController::class, 'create'])
        ->name('master-class.create')->middleware('role:master');
    Route::post('/master-class', [MasterClassController::class, 'store'])
        ->name('master-class.store')->middleware('role:master');
    Route::get('/master-class/{masterClass}/edit', [MasterClassController::class, 'edit'])
        ->name('master-class.edit')->middleware('role:master');
    Route::put('/master-class/{masterClass}', [MasterClassController::class, 'update'])
        ->name('master-class.update')->middleware('role:master');
    Route::get('/master-class/{masterClass}/participants', [MasterClassController::class, 'participants'])
        ->name('master-class.participants')->middleware('role:master');

    // запись на мастер-класс
    Route::get('/register-master-class/{masterClass}', [RegistrationController::class, 'confirm'])
        ->name('registration.confirm');
    Route::post('/register-master-class/{masterClass}', [RegistrationController::class, 'store'])
        ->name('registration.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
