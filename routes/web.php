<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\DashboardController;

Route::get('/', [FrontController::class, 'index'])->name('front.welcome');


Route::get('/login', [FrontController::class, 'login_form'])->name('login');
Route::get('/register', [FrontController::class, 'register_form'])->name('register');