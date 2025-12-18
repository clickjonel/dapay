<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return Inertia::render('login');
    })->name('login');
    
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware(['web','auth'])->group(function () {
    //::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
   Route::resource('program', ProgramController::class);
});