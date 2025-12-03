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
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SettlementController;

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

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes - Dashboard (Settlements & Finance)
Route::get('/dashboard', [SettlementController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Products Routes
Route::resource('products', ProductController::class)->middleware('auth');

// Categories Routes
Route::resource('categories', CategoryController::class)->middleware('auth');

// Attributes Routes
Route::resource('attributes', AttributeController::class)->middleware('auth');

// Profile Routes
Route::get('/profile/edit', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// Orders Routes
Route::resource('orders', OrderController::class)->middleware('auth')->only(['index', 'show', 'update']);

// Media Routes
Route::get('/media', [MediaController::class, 'index'])->middleware('auth')->name('media.index');
Route::get('/media/json', [MediaController::class, 'getMediaJson'])->middleware('auth')->name('media.json');
Route::post('/media', [MediaController::class, 'store'])->middleware('auth')->name('media.store');
Route::delete('/media', [MediaController::class, 'destroy'])->middleware('auth')->name('media.destroy');

// Drivers Routes
Route::resource('drivers', DriverController::class)->middleware('auth');

// Customers Routes
Route::resource('customers', CustomerController::class)->middleware('auth');

// Settings Routes
Route::get('/settings', [SettingsController::class, 'index'])->middleware('auth')->name('settings.index');
Route::put('/settings', [SettingsController::class, 'update'])->middleware('auth')->name('settings.update');
Route::post('/settings/currencies', [SettingsController::class, 'storeCurrency'])->middleware('auth')->name('settings.currencies.store');
Route::put('/settings/currencies/{currency}', [SettingsController::class, 'updateCurrency'])->middleware('auth')->name('settings.currencies.update');
Route::delete('/settings/currencies/{currency}', [SettingsController::class, 'deleteCurrency'])->middleware('auth')->name('settings.currencies.delete');
Route::put('/settings/exchange-rates', [SettingsController::class, 'updateExchangeRates'])->middleware('auth')->name('settings.exchange-rates.update');

// Admins Routes
Route::resource('admins', AdminController::class)->middleware('auth');

// Roles and Permissions Routes
Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->middleware('auth')->name('roles-permissions.index');
Route::post('/roles-permissions/roles', [RolePermissionController::class, 'storeRole'])->middleware('auth')->name('roles-permissions.storeRole');
Route::put('/roles-permissions/roles/{role}', [RolePermissionController::class, 'updateRole'])->middleware('auth')->name('roles-permissions.updateRole');
Route::delete('/roles-permissions/roles/{role}', [RolePermissionController::class, 'destroyRole'])->middleware('auth')->name('roles-permissions.destroyRole');
Route::post('/roles-permissions/permissions', [RolePermissionController::class, 'storePermission'])->middleware('auth')->name('roles-permissions.storePermission');
Route::put('/roles-permissions/permissions/{permission}', [RolePermissionController::class, 'updatePermission'])->middleware('auth')->name('roles-permissions.updatePermission');
Route::delete('/roles-permissions/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->middleware('auth')->name('roles-permissions.destroyPermission');
Route::put('/roles-permissions/roles/{role}/permissions', [RolePermissionController::class, 'updateRolePermissions'])->middleware('auth')->name('roles-permissions.updateRolePermissions');

// Audit Logs Routes
Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('auth')->name('audit-logs.index');
Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->middleware('auth')->name('audit-logs.export');
Route::delete('/audit-logs/all/filtered', [AuditLogController::class, 'destroyAll'])->middleware('auth')->name('audit-logs.destroyAll');
Route::delete('/audit-logs', [AuditLogController::class, 'destroyMultiple'])->middleware('auth')->name('audit-logs.destroyMultiple');
Route::get('/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->middleware('auth')->name('audit-logs.show');
Route::delete('/audit-logs/{auditLog}', [AuditLogController::class, 'destroy'])->middleware('auth')->name('audit-logs.destroy');

// Settlements & Finance Routes
Route::prefix('settlements')->name('settlements.')->middleware('auth')->group(function () {
    Route::get('/history', [SettlementController::class, 'history'])->name('history');
    Route::get('/history/export/{format}', [SettlementController::class, 'exportHistory'])->name('export-history');
    Route::get('/request', [SettlementController::class, 'request'])->name('request');
    Route::post('/request', [SettlementController::class, 'storeRequest'])->name('store-request');
    Route::get('/request/export/{format}', [SettlementController::class, 'exportRequest'])->name('export-request');
    Route::put('/{settlement}/status', [SettlementController::class, 'updateStatus'])->name('update-status');
    Route::get('/discrepancy-reports', [SettlementController::class, 'discrepancyReports'])->name('discrepancy-reports');
    Route::get('/payout-summary', [SettlementController::class, 'payoutSummary'])->name('payout-summary');
    Route::get('/commission-calculator', [SettlementController::class, 'commissionCalculator'])->name('commission-calculator');
});
