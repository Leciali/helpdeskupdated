<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;

Route::middleware(["guest"])->group(function () {
    Route::get("/", [SessionController::class, "loginpage"])->name('loginpage');
    Route::post("/login", [SessionController::class, "login"])->name('login');
});


Route::middleware(["auth"])->group(function () {
    Route::get('/dashboard', [DashboardController::class, "dashboardview"]) -> name('user.dashboard');
    Route::get('/open-ticket',[DashboardController::class,"openticketview"])-> name('user.open-ticket');
    Route::get('/pending-ticket',[DashboardController::class, "pendingticketview"]) -> name('user.pending-ticket');
    Route::get('/solved-ticket',[DashboardController::class, "solvedticketview"]) -> name('user.solved-ticket');
    Route::get('/report', [DashboardController::class, 'reportview']) -> name('user.report');
    Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
});