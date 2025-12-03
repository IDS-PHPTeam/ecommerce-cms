<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::get('/dashboard', function () {
    $productsCount = \App\Models\Product::count();
    return view('home', compact('productsCount'));
})->middleware('auth')->name('dashboard');

// Products Routes
Route::resource('products', ProductController::class)->middleware('auth');

// Categories Routes
Route::resource('categories', CategoryController::class)->middleware('auth');

// Profile Routes
Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// Orders Routes
Route::resource('orders', OrderController::class)->middleware('auth')->only(['index', 'show', 'update']);

// Media Routes
Route::get('/media', [MediaController::class, 'index'])->middleware('auth')->name('media.index');
Route::get('/media/json', [MediaController::class, 'getMediaJson'])->middleware('auth')->name('media.json');
Route::delete('/media', [MediaController::class, 'destroy'])->middleware('auth')->name('media.destroy');

// Drivers Routes
Route::resource('drivers', DriverController::class)->middleware('auth')->only(['index', 'show']);

// Customers Routes
Route::resource('customers', CustomerController::class)->middleware('auth')->only(['index', 'show']);
