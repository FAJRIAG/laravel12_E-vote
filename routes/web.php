<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ResultController;

use App\Http\Controllers\AuthCodeController;
use App\Http\Controllers\Admin\LoginCodeController as AdminLoginCodeController;

// =======================
// Home / Dashboard
// =======================
Route::get('/', [DashboardController::class, 'index'])->name('home');

// =======================
// Auth (email+password) + Login via CODE
// =======================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Login via kode (guest hanya bisa login, TIDAK bisa request kode)
    Route::get('/login-code', [AuthCodeController::class, 'showForm'])->name('login.code.show');
    Route::post('/login-code', [AuthCodeController::class, 'authenticate'])->name('login.code');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// =======================
// Admin Area (hanya admin yang bisa buat & kirim kode)
// =======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

    Route::resource('elections', ElectionController::class)->parameters(['elections' => 'election']);
    Route::resource('elections.positions', PositionController::class)->shallow();
    Route::resource('positions.candidates', CandidateController::class)->shallow();

    // Hasil untuk ADMIN
    Route::get('elections/{election}/results', [ResultController::class, 'index'])->name('elections.results.index');
    Route::get('elections/{election}/results/json', [ResultController::class, 'json'])->name('elections.results.json');
    Route::get('elections/{election}/results/pdf', [ResultController::class, 'pdf'])->name('elections.results.pdf');

    // Kelola Kode Login (buat, kirim, export)
    Route::resource('codes', AdminLoginCodeController::class)->only(['index','create','store','destroy','update']);
    Route::post('codes/{code}/toggle', [AdminLoginCodeController::class, 'toggle'])->name('codes.toggle');

    Route::get('codes/export/csv', [AdminLoginCodeController::class, 'exportCsv'])->name('codes.export.csv');
    Route::get('codes/export/pdf', [AdminLoginCodeController::class, 'exportPdf'])->name('codes.export.pdf');
});

// =======================
// Voting (User / Pemilih)
// =======================
Route::middleware(['auth'])->group(function () {
    Route::get('/e/{election}', [VoteController::class, 'selectElection'])->name('vote.select.election');

    Route::get('/e/{election}/vote', [VoteController::class, 'index'])
        ->middleware('election.open')->name('vote.index');
    Route::post('/e/{election}/vote', [VoteController::class, 'store'])
        ->middleware('election.open')->name('vote.store');

    Route::get('/e/{election}/results', [ResultController::class, 'userResults'])->name('results.index');
});

// (Opsional, hanya untuk tes lokal mailable; sebaiknya dibatasi ke env local)
// Route::get('/test-mailable', function () {
//     $to = 'you@example.com';
//     $code = 'ABCD-1234-EFGH';
//     $loginUrl = route('login');
//     \Mail::to($to)->send(new \App\Mail\LoginCodeMail($code, $loginUrl));
//     return '✅ Email test sudah dikirim ke ' . $to;
// });
