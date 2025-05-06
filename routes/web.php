<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;

Route::middleware(["guest"])->group(function () {
    Route::get("/", [SessionController::class, "loginpage"])->name('loginpage');
    Route::post("/login", [SessionController::class, "login"])->name('login');
});


Route::middleware(["auth"])->group(function () {
    Route::get('/dashboard', [TicketController::class, "dashboardView"]) -> name('user.dashboard');
    Route::get('/open-ticket', [TicketController::class, "openTicketView"]) -> name('user.open-ticket');
    Route::get('/pending-ticket', [TicketController::class, "pendingTicketView"]) -> name('user.pending-ticket');
    Route::get('/solved-ticket', [TicketController::class, "solvedTicketView"]) -> name('user.solved-ticket');
    Route::get('/report', [TicketController::class, 'reportView']) -> name('user.report');
    
    // Route untuk membuat tiket baru
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    
    // Route untuk update status tiket
    Route::patch('/tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    
    // Route untuk detail tiket
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    // Route logout
    Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
});