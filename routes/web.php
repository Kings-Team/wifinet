<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PemasanganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('isNoAuth');
Route::post('/login', [AuthController::class, 'loginAction'])->name('login.action');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('isAuth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/user', [UserController::class, 'index'])->name('user');
Route::post('/user/post', [UserController::class, 'store'])->name('user.add');
Route::put('/user/{id}/update', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');


Route::get('/mitra', [MitraController::class, 'index'])->name('mitra');
Route::post('/mitra-post', [MitraController::class, 'store'])->name('mitra.post');
Route::put('/mitra/{id}/update', [MitraController::class, 'update'])->name('mitra.update');
Route::delete('/mitra/{id}/delete', [MitraController::class, 'delete'])->name('mitra.delete');

Route::get('/pemasangan', [PemasanganController::class, 'index'])->name('pemasangan');
Route::post('/pemasangan/post', [PemasanganController::class, 'store'])->name('pemasangan.add');
Route::put('/pemasangan/{id}/update', [PemasanganController::class, 'update'])->name('pemasangan.update');
Route::put('/pemasangan/{id}/assignment-sales', [PemasanganController::class, 'assignment'])->name('pemasangan.assignment.sales');
Route::put('/pemasangan/{id}/assignment-teknisi', [PemasanganController::class, 'assignmentTeknisi'])->name('pemasangan.assignment.teknisi');
Route::put('/pemasangan/{id}/survey', [PemasanganController::class, 'updateSurvey'])->name('pemasangan.survey');
Route::put('/pemasangan/{id}/instalasi', [PemasanganController::class, 'updateInstalasi'])->name('pemasangan.instalasi');
Route::put('/pemasangan/{id}/aktivasi', [PemasanganController::class, 'updateAktivasi'])->name('pemasangan.aktivasi');
Route::put('/pemasangan/{id}/pembayaran', [PemasanganController::class, 'updatePembayaran'])->name('pemasangan.pembayaran');
