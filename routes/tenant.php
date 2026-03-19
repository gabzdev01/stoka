<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ShoppingListController;
use App\Http\Controllers\RestocksController;
use App\Http\Controllers\SupplierBalancesController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;

Route::middleware([
    "web",
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Auth routes
    Route::get("/login", [LoginController::class, "showLogin"])->name("login");
    Route::post("/login", [LoginController::class, "login"]);
    Route::post("/logout", [LoginController::class, "logout"])->name("logout");

    // Password reset (public — owner may not be logged in)
    Route::get('/password-reset',           [PasswordResetController::class, 'show'])->name('password-reset.show');
    Route::post('/password-reset/request',  [PasswordResetController::class, 'requestReset'])->name('password-reset.request');
    Route::post('/password-reset/confirm',  [PasswordResetController::class, 'confirm'])->name('password-reset.confirm');

    // Protected routes
    Route::middleware("auth.custom")->group(function () {

        // Profile (staff + owner)
        Route::get('/profile',      [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin');

        // Close shift + summary — must register BEFORE /shifts/{shift} wildcard
        Route::get("/shifts/close",           [ShiftsController::class, "closeForm"])->name("shifts.close");
        Route::post("/shifts/close",          [ShiftsController::class, "close"])->name("shifts.close.submit");
        Route::get("/shifts/{shift}/summary", [ShiftsController::class, "summary"])->name("shifts.summary");

        // Owner only
        Route::middleware("owner.only")->group(function () {
            Route::get("/", [DashboardController::class, "index"])->name("dashboard");

            Route::get("/suppliers", [SuppliersController::class, "index"])->name("suppliers.index");
            Route::post("/suppliers", [SuppliersController::class, "store"])->name("suppliers.store");
            Route::put("/suppliers/{supplier}", [SuppliersController::class, "update"])->name("suppliers.update");
            Route::delete("/suppliers/{supplier}", [SuppliersController::class, "destroy"])->name("suppliers.destroy");

            Route::get("/products", [ProductsController::class, "index"])->name("products.index");
            Route::get("/products/create", [ProductsController::class, "create"])->name("products.create");
            Route::post("/products", [ProductsController::class, "store"])->name("products.store");
            Route::get("/products/{product}/edit", [ProductsController::class, "edit"])->name("products.edit");
            Route::put("/products/{product}", [ProductsController::class, "update"])->name("products.update");
            Route::post("/products/{product}/toggle", [ProductsController::class, "toggle"])->name("products.toggle");

            // Shifts (owner view)
            Route::get("/shifts",           [ShiftsController::class, "index"])->name("shifts.index");
            Route::get("/shifts/{shift}",   [ShiftsController::class, "show"])->name("shifts.show");

            // Shopping list
            Route::get("/shopping-list", [ShoppingListController::class, "index"])->name("shopping-list.index");

            // Restocks
            Route::get("/restocks",         [RestocksController::class, "index"])->name("restocks.index");
            Route::get("/restocks/create",  [RestocksController::class, "create"])->name("restocks.create");
            Route::post("/restocks",        [RestocksController::class, "store"])->name("restocks.store");

            // Supplier balances
            Route::get("/supplier-balances",                          [SupplierBalancesController::class, "index"])->name("supplier-balances.index");
            Route::post("/supplier-balances/{supplier}/payment",      [SupplierBalancesController::class, "recordPayment"])->name("supplier-balances.payment");

            // Credit management
            Route::get("/credit",                     [CreditController::class, "index"])->name("credit.index");
            Route::post("/credit/{customer}/payment", [CreditController::class, "recordPayment"])->name("credit.payment");

            // Settings
            Route::get('/settings',                              [SettingsController::class, 'index'])->name('settings.index');
            Route::post('/settings/account',                     [SettingsController::class, 'saveAccount'])->name('settings.account');
            Route::post('/settings/password',                    [SettingsController::class, 'changePassword'])->name('settings.password');
            Route::post('/settings/shop',                        [SettingsController::class, 'saveShop'])->name('settings.shop');
            Route::post('/settings/receipts',                    [SettingsController::class, 'saveReceipts'])->name('settings.receipts');
            Route::post('/settings/alerts',                      [SettingsController::class, 'saveAlerts'])->name('settings.alerts');
            Route::post('/settings/staff',                       [SettingsController::class, 'addStaff'])->name('settings.staff.add');
            Route::post('/settings/staff/{user}/toggle',         [SettingsController::class, 'toggleStaffStatus'])->name('settings.staff.toggle');
            Route::post('/settings/staff/{user}/reset-pin',      [SettingsController::class, 'resetStaffPin'])->name('settings.staff.reset-pin');
            Route::post('/settings/staff/{user}/remove',         [SettingsController::class, 'removeStaff'])->name('settings.staff.remove');
            Route::get('/settings/export',                       [SettingsController::class, 'export'])->name('settings.export');
        });

        // Staff + owner — sales flow
        Route::post("/shifts/open",  [ShiftsController::class, "open"])->name("shifts.open");
        Route::get("/sales",         [SalesController::class, "index"])->name("sales.index");
        Route::post("/sales",        [SalesController::class, "store"])->name("sales.store");
        Route::get("/sales/shift",   [SalesController::class, "activeShift"])->name("sales.shift");
        Route::get("/sales/history",  [SalesController::class, "history"])->name("sales.history");
        Route::post("/sales/cart",     [SalesController::class, "storeCart"])->name("sales.cart");
        Route::get("/customers/lookup",[SalesController::class, "customerLookup"])->name("customers.lookup");
        Route::post("/sales/{sale}/void",  [SalesController::class, "void"])->name("sales.void");
        Route::get("/sales/receipt/{ids}", [SalesController::class, "receipt"])->name("sales.receipt");
    });

});
