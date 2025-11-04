<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\brandcontroller;
use App\Http\Controllers\productController;
use App\Http\Controllers\labeltypeController;
use App\Http\Controllers\unitController;
use App\Http\Controllers\dunitController;
use App\Http\Controllers\shipperController;
use App\Http\Controllers\statusController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here are the manually defined API routes for your application.
|
*/

// A simple route to check if the API is responsive.
Route::get('/ping', fn() => response()->json(['message' => 'API is working']));

// --- Buyer Routes ---
Route::get('/buyers', [BuyerController::class, 'indexApi'])->name('api.buyers.index');
Route::post('/buyers', [BuyerController::class, 'storeApi'])->name('api.buyers.store');
Route::get('/buyers/{buyer}', [BuyerController::class, 'showApi'])->name('api.buyers.show');

// --- Brand Routes ---
Route::get('/brands', [BrandController::class, 'indexApi'])->name('api.brands.index');
Route::post('/brands', [BrandController::class, 'storeApi'])->name('api.brands.store');
Route::get('/brands/{brand}', [BrandController::class, 'showApi'])->name('api.brands.show');

// --- Product Routes ---
Route::get('/products', [ProductController::class, 'indexApi'])->name('api.products.index');
Route::post('/products', [ProductController::class, 'storeApi'])->name('api.products.store');
Route::get('/products/{product}', [ProductController::class, 'showApi'])->name('api.products.show');

// --- Label Type Routes ---
Route::get('/label-types', [LabelTypeController::class, 'indexApi'])->name('api.label-types.index');
Route::post('/label-types', [LabelTypeController::class, 'storeApi'])->name('api.label-types.store');
Route::get('/label-types/{label_type}', [LabelTypeController::class, 'showApi'])->name('api.label-types.show');

// --- Unit Routes ---
Route::get('/units', [UnitController::class, 'indexApi'])->name('api.units.index');
Route::post('/units', [UnitController::class, 'storeApi'])->name('api.units.store');
Route::get('/units/{unit}', [UnitController::class, 'showApi'])->name('api.units.show');

// --- Dimension Unit Routes ---
Route::get('/d-units', [DUnitController::class, 'indexApi'])->name('api.d-units.index');
Route::post('/d-units', [DUnitController::class, 'storeApi'])->name('api.d-units.store');
Route::get('/d-units/{d_unit}', [DUnitController::class, 'showApi'])->name('api.d-units.show');

// --- Shipper Routes ---
Route::get('/shippers', [ShipperController::class, 'indexApi'])->name('api.shippers.index');
Route::post('/shippers', [ShipperController::class, 'storeApi'])->name('api.shippers.store');
Route::get('/shippers/{shipper}', [ShipperController::class, 'showApi'])->name('api.shippers.show');

// --- Status Routes ---
Route::get('/statuses', [StatusController::class, 'indexApi'])->name('api.statuses.index');
Route::post('/statuses', [StatusController::class, 'storeApi'])->name('api.statuses.store');
Route::get('/statuses/{status}', [StatusController::class, 'showApi'])->name('api.statuses.show');

// --- Supplier Routes ---
Route::get('/suppliers', action: [SupplierController::class, 'indexApi'])->name('api.suppliers.index');
Route::post('/suppliers', action: [SupplierController::class, 'storeApi'])->name('api.suppliers.store');
Route::get('/suppliers/{supplier}', action: [SupplierController::class, 'showApi'])->name('api.suppliers.show');

