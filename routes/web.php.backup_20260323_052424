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

    // Content management
    Route::get('/admin/articles',                    [AdminController::class, 'articles'])->name('admin.articles');
    Route::get('/admin/articles/create',             [AdminController::class, 'articleCreate'])->name('admin.articles.create');
    Route::post('/admin/articles',                   [AdminController::class, 'articleStore'])->name('admin.articles.store');
    Route::get('/admin/articles/{id}/edit',          [AdminController::class, 'articleEdit'])->name('admin.articles.edit');
    Route::post('/admin/articles/{id}',              [AdminController::class, 'articleUpdate'])->name('admin.articles.update');
    Route::post('/admin/articles/{id}/delete',       [AdminController::class, 'articleDelete'])->name('admin.articles.delete');

    Route::get('/admin/testimonials',                [AdminController::class, 'testimonials'])->name('admin.testimonials');
    Route::get('/admin/testimonials/create',         [AdminController::class, 'testimonialCreate'])->name('admin.testimonials.create');
    Route::post('/admin/testimonials',               [AdminController::class, 'testimonialStore'])->name('admin.testimonials.store');
    Route::get('/admin/testimonials/{id}/edit',      [AdminController::class, 'testimonialEdit'])->name('admin.testimonials.edit');
    Route::post('/admin/testimonials/{id}',          [AdminController::class, 'testimonialUpdate'])->name('admin.testimonials.update');
    Route::post('/admin/testimonials/{id}/delete',   [AdminController::class, 'testimonialDelete'])->name('admin.testimonials.delete');

    Route::get('/admin/demo-products',               [AdminController::class, 'demoProducts'])->name('admin.demo-products');
    Route::post('/admin/demo-products/{id}/toggle',  [AdminController::class, 'demoProductToggle'])->name('admin.demo-products.toggle');
    Route::get('/admin/demo-products/create',         [AdminController::class, 'demoProductCreate'])->name('admin.demo-products.create');
    Route::post('/admin/demo-products',               [AdminController::class, 'demoProductStore'])->name('admin.demo-products.store');
    Route::get('/admin/demo-products/{id}/edit',      [AdminController::class, 'demoProductEdit'])->name('admin.demo-products.edit');
    Route::post('/admin/demo-products/{id}/update',   [AdminController::class, 'demoProductUpdate'])->name('admin.demo-products.update');
    Route::post('/admin/demo-products/{id}/delete',   [AdminController::class, 'demoProductDelete'])->name('admin.demo-products.delete');
});
