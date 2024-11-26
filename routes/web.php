<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureUserHasPackage; 


Route::get('/', [FrontController::class, 'index'])->name('front.welcome');



#authentication 
Route::get('/login', [AuthController::class, 'login_form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'register_form'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/forgot', [AuthController::class, 'show_email_form'])->name('forgot'); 
Route::post('/forgot', [AuthController::class, 'send_reset_email'])->name('forgot'); 

Route::get('/verify/{token}/{email}', [AuthController::class, 'verify_email_address'])->name('email.verify');
Route::get('/reset/{token}/{email}', [AuthController::class, 'show_change_password_form'])->name('update_password'); 
Route::post('/update-password', [AuthController::class, 'change_password'])->name('change_password'); 


#Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index'); 
Route::get('/trx-history', [DashboardController::class, 'load_more_transactions'])->name('dashboard.trx_history'); 
Route::post('/ykMbJXg2QRnlBZvxCZb', [DashboardController::class, 'select_package'])->name('dashboard.select_package');

#main admin
Route::get('/main/settings',  [SettingsController::class, 'index'])->name('admin.settings');
Route::get('/main/settings/packages', [SettingsController::class, 'packages'])->name('admin.packages.index'); 
Route::post('/main/settings/packages', [SettingsController::class, 'create_package'])->name('admin.packages.create_package'); 
Route::put('/main/settings/package/{id}', [SettingsController::class, 'update_package'])->name('admin.package.update'); 
Route::delete('/main/settings/package/{id}', [SettingsController::class, 'destroy_package'])->name('admin.package.destroy');
Route::post('/main/settings/update_parameters', [SettingsController::class, 'update_parameters'])->name('admin.package.update_parameters');

//reward settings
Route::get('/main/reward', [SettingsController::class, 'reward_settings'])->name('admin.reward.settings'); 
Route::post('/main/reward', [SettingsController::class, 'update_reward_data'])->name('admin.reward.settings'); 

Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');