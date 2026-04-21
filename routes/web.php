<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HoraController;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Se o usuário já estiver logado, desloga ele e manda para o login
    if (Auth::check()) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/salvar-horas', [HoraController::class, 'store'])
    ->middleware(['auth'])
    ->name('worklog.store');

Route::get('/relatorio', [HoraController::class, 'index'])
    ->middleware(['auth'])
    ->name('relatorio.index');
    
Route::get('/relatorio/exportar', [HoraController::class, 'export'])->name('relatorio.export');

require __DIR__.'/auth.php';
