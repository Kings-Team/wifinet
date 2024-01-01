<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MitraController;
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
Route::post('/user-role-permission', [UserController::class, 'store'])->name('user.add');
Route::delete('/user/{id}/delete', [UserController::class, 'delete'])->name('user.delete');


Route::get('/mitra', [MitraController::class, 'index'])->name('mitra');
Route::post('/mitra-post', [MitraController::class, 'store'])->name('mitra.post');
Route::put('/mitra/{id}/update', [MitraController::class, 'update'])->name('mitra.update');
Route::delete('/mitra/{id}/delete', [MitraController::class, 'delete'])->name('mitra.delete');
