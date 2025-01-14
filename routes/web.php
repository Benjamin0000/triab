<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingsController as AdminSettings;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TriabWheelController;
use App\Http\Controllers\Eshop\EshopController;
use App\Http\Controllers\Eshop\PosController as EshopPos;
use App\Http\Middleware\EnsureUserHasPackage;
use App\Http\Controllers\TriabMarketController;
use App\Http\Controllers\PosController;
use App\Models\Order; 

Route::get('/', [FrontController::class, 'index'])->name('front.welcome');
Route::get('/services', [FrontController::class, 'services'])->name('front.services');
Route::get('/about', [FrontController::class, 'about'])->name('front.about');
Route::get('/terms', [FrontController::class, 'terms'])->name('front.terms');
Route::get('/privacy', [FrontController::class, 'privacy'])->name('front.privacy'); 

#authentication 
Route::get('/login', [AuthController::class, 'login_form'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [AuthController::class, 'register_form'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/forgot', [AuthController::class, 'show_email_form'])->name('forgot'); 
Route::post('/forgot', [AuthController::class, 'send_reset_email'])->name('forgot'); 

Route::get('/verify/{token}/{email}', [AuthController::class, 'verify_email_address'])->name('email.verify');
Route::get('/reset/{token}/{email}', [AuthController::class, 'show_change_password_form'])->name('password_reset_form'); 
Route::post('/update-password', [AuthController::class, 'change_password'])->name('change_password'); 



#Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index'); 
Route::get('/trx-history', [DashboardController::class, 'load_more_transactions'])->name('dashboard.trx_history'); 
Route::post('/ykMbJXg2QRnlBZvxCZb', [DashboardController::class, 'select_package'])->name('dashboard.select_package');

#community
Route::get('/community', [TriabWheelController::class, 'index'])->name('community.index');
Route::post('/move-to-main', [TriabWheelController::class, 'move_to_main'])->name('community.move_to_main');

#triab market
Route::get('/triab-market', [TriabMarketController::class, 'index'])->name('triab_market.index');
Route::get('/triab-market/{id}/shops', [TriabMarketController::class, 'show_shops'])->name('triab_market.show_shops');


//Eshop
Route::get('/e-shop', [EshopController::class, 'index'])->name('eshop'); 
Route::get('/e-shop/{id}/show', [EshopController::class, 'show'])->name('eshop.dashboard'); 
Route::get('/e-shop/create-shop', [EshopController::class, 'create'])->name('eshop.create');
Route::post('/e-shop/create', [EshopController::class, 'store'])->name('eshop.save');
Route::get('/e-shop/{id}/edit', [EshopController::class, 'edit'])->name('eshop.edit');
Route::put('/e-shop/{id}', [EshopController::class, 'update'])->name('eshop.update');
Route::delete('/e-shop/{id}', [EshopController::class, 'destroy'])->name('eshop.delete'); 
Route::post('/set-eshop-fee/{id}', [EshopController::class, 'set_fee'])->name('eshop.set_fee'); 

Route::get('/e-shop/pos/{id}', [EshopPos::class, 'index'])->name('eshop.pos'); //POS settings for front desk. 
Route::post('/e-shop/create_staff', [EshopPos::class, 'create_staff'])->name('eshop.create_staff'); 
Route::post('/e-shop/update-staff', [EshopPos::class, 'update_staff'])->name('eshop.update_staff'); 
Route::delete('/e-shop/delete-staff/{id}', [EshopPos::class, 'delete_staff'])->name('eshop.delete_staff'); 


Route::get('/e-shop/products/{shop_id}/{parent_id?}', [EshopController::class, 'products'])->name('eshop.products');
Route::get('/e-shop/add-product/{shop_id}/{parent_id?}', [EshopController::class, 'show_create_product_page'])->name('eshop.add_product');
Route::post('/e-shop/create-product-category/{shop_id}', [EshopController::class, 'add_product_category'])->name('eshop.product.create_category');
Route::post('/e-shop/update-product-category', [EshopController::class, 'update_product_category'])->name('eshop.product.update_category');
Route::get('/e-shop/edit-product/{id}', [EshopController::class, 'show_edit_product_page'])->name('eshop.show_edit_product');
Route::post('/e-shop/create-product/{shop_id}', [EshopController::class, 'add_product'])->name('eshop.product.create');
Route::post('/e-shop/update-product/{id}', [EshopController::class, 'update_product'])->name('eshop.product.update');
Route::delete('/e-shop/product/{id}', [EshopController::class, 'delete_product'])->name('eshop.product.delete_product'); 

Route::post('/e-shop/add-stock/{id}', [EshopController::class, 'add_stock'])->name('eshop.product.add_stock'); 
Route::post('/e-shop/remove-stock/{id}', [EshopController::class, 'remove_stock'])->name('eshop.product.remove_stock'); 
Route::get('/e-shop/stock-history/{id}', [EshopController::class, 'stock_history'])->name('eshop.stock.history'); 


Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');









#main admin
Route::get('/main/settings',  [AdminSettings::class, 'index'])->name('admin.settings');
Route::get('/main/settings/packages', [AdminSettings::class, 'packages'])->name('admin.packages.index');
Route::post('/main/settings/packages', [AdminSettings::class, 'create_package'])->name('admin.packages.create_package');
Route::put('/main/settings/package/{id}', [AdminSettings::class, 'update_package'])->name('admin.package.update');
Route::delete('/main/settings/package/{id}', [AdminSettings::class, 'destroy_package'])->name('admin.package.destroy');
Route::post('/main/settings/update_parameters', [AdminSettings::class, 'update_package_parameters'])->name('admin.package.update_parameters');

#eshop
Route::get('/main/settings/eshop', [AdminSettings::class, 'eshop_settings'])->name('admin.eshop.settings'); 
Route::post('/main/settings/create-eshop-category', [AdminSettings::class, 'create_eshop_category'])->name('admin.create_eshop_category');
Route::put('/main/settings/update-eshop-category/{id}', [AdminSettings::class, 'update_eshop_category'])->name('admin.update_eshop_category'); 
Route::delete('/main/settings/delete-eshop-category/{id}', [AdminSettings::class, 'delete_eshop_category'])->name('admin.delete_eshop_category'); 

//reward settings
Route::get('/main/reward', [AdminSettings::class, 'reward_settings'])->name('admin.reward.settings'); 
Route::post('/main/reward', [AdminSettings::class, 'update_reward_data'])->name('admin.reward.settings'); 






//POS
Route::get('/pos/{id}/{any?}', [PosController::class, 'index'])
    ->where(['id' => '[0-9a-fA-F\-]+', 'any' => '.*'])
    ->name('pos.index');



Route::get('/pos-login', [PosController::class, 'login_page'])->name('pos.login');
Route::post('/pos-login-logger', [PosController::class, 'login'])->name('pos.logger');
