<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\BarangayOrganizationalIndicatorsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ReportController;
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
        
    Route::get('/dashboard-geographic', [DashboardController::class,'geographicDashboard']);
    Route::get('/dashboard-program-indicators', [DashboardController::class,'programIndicatorsDashboard']);
        
    Route::resource('program', ProgramController::class);
    Route::resource('barangay', BarangayController::class);

    Route::get('/indicator/organizational',[IndicatorController::class,'organizationalIndicatorsPage']);
    Route::get('/indicator/program',[IndicatorController::class,'programIndicatorsPage']);

    Route::post('/indicator/organizational/create',[IndicatorController::class,'createOrgIndicator']);
    Route::post('/indicator/program/create',[IndicatorController::class,'createProgramIndicator']);

    Route::patch('/indicator/organizational/{id}',[IndicatorController::class,'updateOrgIndicator']);
    Route::patch('/indicator/program/{id}',[IndicatorController::class,'updateProgramIndicator']);

    Route::delete('/indicator/organizational/{id}',[IndicatorController::class,'disableOrgIndicator']);
    Route::delete('/indicator/program/{id}',[IndicatorController::class,'disableProgramIndicator']);

    Route::post('/indicator/disaggregations/set/{id}',[IndicatorController::class,'setProgramIndicatorDisaggregations']);

    Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/report/create', [ReportController::class, 'showCreateReportPage']);
    Route::post('/report', [ReportController::class, 'createReport']);

    Route::post('/barangay-set-org_indicators', [BarangayOrganizationalIndicatorsController::class, 'createBarangayOrgIndicators']);

    Route::post('/logout', [AuthController::class, 'logout']);
});