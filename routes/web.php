<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultController;

// Tambahan untuk login via kode
use App\Http\Controllers\AuthCodeController;
use App\Http\Controllers\Admin\LoginCodeController as AdminLoginCodeController;

// =======================
// Home / Dashboard
// =======================
Route::get('/', [DashboardController::class, 'index'])->name('home');

// =======================
// Auth (email+password)
// =======================
Route::middleware('guest')->group(function () {
    /* Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
       Route::post('/register', [AuthController::class, 'register'])->name('register'); */
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =======================
// Login via CODE untuk pemilih (Tambahan)
// =======================
Route::middleware('guest')->group(function () {
    Route::get('/login-code', [AuthCodeController::class, 'showForm'])->name('login.code.show');
    Route::post('/login-code', [AuthCodeController::class, 'authenticate'])->name('login.code');
});

// =======================
// Admin Area
// =======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

    Route::resource('elections', ElectionController::class)->parameters(['elections' => 'election']);
    Route::resource('elections.positions', PositionController::class)->shallow();
    Route::resource('positions.candidates', CandidateController::class)->shallow();

    // Hasil untuk ADMIN
    Route::get('elections/{election}/results', [ResultController::class, 'index'])
        ->name('elections.results.index');
    Route::get('elections/{election}/results/json', [ResultController::class, 'json'])
        ->name('elections.results.json');
    Route::get('elections/{election}/results/pdf', [ResultController::class, 'pdf'])
        ->name('elections.results.pdf');

    // =======================
    // Admin: kelola Kode Login (Tambahan)
    // =======================
    Route::resource('codes', AdminLoginCodeController::class)->only(['index','create','store','destroy','update']);
    Route::post('codes/{code}/toggle', [AdminLoginCodeController::class, 'toggle'])->name('codes.toggle');

    Route::get('codes/export/csv', [AdminLoginCodeController::class, 'exportCsv'])
    ->name('codes.export.csv');

    Route::get('codes/export/pdf', [AdminLoginCodeController::class, 'exportPdf'])
    ->name('codes.export.pdf');
});

// =======================
// Voting (User / Pemilih)
// =======================
Route::middleware(['auth'])->group(function () {
    // Pilih election
    Route::get('/e/{election}', [VoteController::class, 'selectElection'])->name('vote.select.election');

    // Voting
    Route::get('/e/{election}/vote', [VoteController::class, 'index'])
        ->middleware('election.open')->name('vote.index');
    Route::post('/e/{election}/vote', [VoteController::class, 'store'])
        ->middleware('election.open')->name('vote.store');

    // Hasil untuk USER (ringkas)
    Route::get('/e/{election}/results', [ResultController::class, 'userResults'])
        ->name('results.index');
});
