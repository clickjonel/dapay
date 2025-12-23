<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicatorController;
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

    Route::get('/indicator/organizational',[IndicatorController::class,'organizationalIndicatorsPage']);
    Route::get('/indicator/program',[IndicatorController::class,'programIndicatorsPage']);

    Route::post('/indicator/organizational/create',[IndicatorController::class,'createOrgIndicator']);
    Route::post('/indicator/program/create',[IndicatorController::class,'createProgramIndicator']);

    Route::patch('/indicator/organizational/{id}',[IndicatorController::class,'updateOrgIndicator']);
    Route::patch('/indicator/program/{id}',[IndicatorController::class,'updateProgramIndicator']);

    Route::delete('/indicator/organizational/{id}',[IndicatorController::class,'disableOrgIndicator']);
    Route::delete('/indicator/program/{id}',[IndicatorController::class,'disableProgramIndicator']);

    Route::post('/indicator/disaggregations/set/{id}',[IndicatorController::class,'setProgramIndicatorDisaggregations']);
});