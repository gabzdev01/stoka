<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\MarketingController;
use App\Http\Middleware\SuperAdminAuth;
use Illuminate\Support\Facades\Route;

// Marketing home — domain-constrained to central domain only
// Must be registered before tenant routes (which are loaded in TenancyServiceProvider::boot)
// Uses domain constraint so it does not conflict with the tenant GET / (dashboard) route
Route::domain('tempforest.com')->middleware('web')->group(function () {
    Route::get('/', [MarketingController::class, 'index'])->name('marketing.home');
    Route::get('/register', [MarketingController::class, 'registerForm'])->name('marketing.register');
    Route::post('/register', [MarketingController::class, 'register'])->name('marketing.register.store');
    Route::get('/register/welcome', [MarketingController::class, 'registerWelcome'])->name('marketing.register.welcome');
    Route::get('/insights', [MarketingController::class, 'insights'])->name('marketing.insights');
    Route::get('/insights/{slug}', [MarketingController::class, 'insight'])->name('marketing.insight');
});

// ── Super Admin ───────────────────────────────────────────────────────
Route::get('/admin/login',  [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout',[AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(SuperAdminAuth::class)->group(function () {
    Route::get('/admin',                              [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/tenants/create',               [AdminController::class, 'createForm'])->name('admin.tenants.create');
    Route::post('/admin/tenants',                     [AdminController::class, 'store'])->name('admin.tenants.store');
    Route::post('/admin/tenants/{id}/toggle',         [AdminController::class, 'toggle'])->name('admin.tenants.toggle');
    Route::get('/admin/inquiries',                    [AdminController::class, 'inquiries'])->name('admin.inquiries');
    Route::get('/admin/demo-visits',                  [AdminController::class, 'demoVisits'])->name('admin.demo-visits');
    Route::get('/admin/tenants/{id}/detail',          [AdminController::class, 'tenantDetail'])->name('admin.tenant.detail');
    Route::post('/admin/tenants/{id}/shop-toggle',    [AdminController::class, 'shopToggle'])->name('admin.tenants.shop-toggle');
    Route::post('/admin/tenants/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.tenants.reset-password');
});
