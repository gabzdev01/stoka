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
use App\Http\Controllers\ShiftsController;

Route::middleware([
    "web",
    InitializeTenancyBySubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    // Auth routes
    Route::get("/login", [LoginController::class, "showLogin"])->name("login");
    Route::post("/login", [LoginController::class, "login"]);
    Route::post("/logout", [LoginController::class, "logout"])->name("logout");

    // Protected routes
    Route::middleware("auth.custom")->group(function () {

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
        });

        // Staff + owner — sales flow
        Route::post("/shifts/open",  [ShiftsController::class, "open"])->name("shifts.open");
        Route::get("/sales",         [SalesController::class, "index"])->name("sales.index");
        Route::get("/sales/shift",   [SalesController::class, "activeShift"])->name("sales.shift");
        Route::get("/sales/history", [SalesController::class, "history"])->name("sales.history");
        Route::get("/shifts/close",  [ShiftsController::class, "closeForm"])->name("shifts.close");

    });

});
