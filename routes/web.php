<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\brandcontroller;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\SupplierController; // <-- Added Supplier
use Illuminate\Support\Facades\Route;

Route::get('/',[loginController::class, 'login'])->name('login');
Route::post('/authenticate', [loginController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [loginController::class, 'logout'])->name('logout');

    // Monitoring page
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

//Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Brand Routes
    Route::prefix('brand')->group(function () {
        Route::get('/', [brandcontroller::class, 'index'])->name('brand.index');
        Route::get('/create', [brandcontroller::class, 'create'])->name('brand.create');
        Route::post('/store', [brandcontroller::class, 'store'])->name('brand.store');
        Route::get('/{id}/edit', [brandcontroller::class, 'edit'])->name('brand.edit');
        Route::put('/{id}', [brandcontroller::class, 'update'])->name('brand.update');
        Route::delete('/{id}', [brandcontroller::class, 'destroy'])->name('brand.destroy');
    });

    // Color Set Routes
    Route::prefix('colorset')->group(function () {
        Route::get('/', [\App\Http\Controllers\colorsetController::class, 'index'])->name('colorset.index');
        Route::get('/create', [\App\Http\Controllers\colorsetController::class, 'create'])->name('colorset.create');
        Route::post('/store', [\App\Http\Controllers\colorsetController::class, 'store'])->name('colorset.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\colorsetController::class, 'edit'])->name('colorset.edit');
        Route::put('/{id}', [\App\Http\Controllers\colorsetController::class, 'update'])->name('colorset.update');
        Route::get('/{id}/delete', [\App\Http\Controllers\colorsetController::class, 'destroy'])->name('colorset.delete');
    });

    // Shipper Routes
    Route::prefix('shipper')->group(function () {
        Route::get('/', [\App\Http\Controllers\shipperController::class, 'index'])->name('shipper.index');
        Route::get('/create', [\App\Http\Controllers\shipperController::class, 'create'])->name('shipper.create');
        Route::post('/store', [\App\Http\Controllers\shipperController::class, 'store'])->name('shipper.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\shipperController::class, 'edit'])->name('shipper.edit');
        Route::put('/{id}', [\App\Http\Controllers\shipperController::class, 'update'])->name('shipper.update');
        Route::delete('/{id}', [\App\Http\Controllers\shipperController::class, 'destroy'])->name('shipper.destroy');
    });

    // Label Type Routes
    Route::prefix('labeltype')->group(function () {
        Route::get('/', [\App\Http\Controllers\labeltypeController::class, 'index'])->name('labeltype.index');
        Route::get('/create', [\App\Http\Controllers\labeltypeController::class, 'create'])->name('labeltype.create');
        Route::post('/store', [\App\Http\Controllers\labeltypeController::class, 'store'])->name('labeltype.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\labeltypeController::class, 'edit'])->name('labeltype.edit');
        Route::put('/{id}', [\App\Http\Controllers\labeltypeController::class, 'update'])->name('labeltype.update');
        Route::delete('/{id}', [\App\Http\Controllers\labeltypeController::class, 'destroy'])->name('labeltype.destroy');
    });

    // Buyer Routes
    Route::prefix('buyer')->group(function () {
        Route::get('/', [\App\Http\Controllers\buyerController::class, 'index'])->name('buyer.index');
        Route::get('/create', [\App\Http\Controllers\buyerController::class, 'create'])->name('buyer.create');
        Route::post('/store', [\App\Http\Controllers\buyerController::class, 'store'])->name('buyer.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\buyerController::class, 'edit'])->name('buyer.edit');
        Route::put('/{id}', [\App\Http\Controllers\buyerController::class, 'update'])->name('buyer.update');
        Route::delete('/{id}', [\App\Http\Controllers\buyerController::class, 'destroy'])->name('buyer.destroy');
    });

    // Status Routes
    Route::prefix('status')->group(function () {
        Route::get('/', [\App\Http\Controllers\statusController::class, 'index'])->name('status.index');
        Route::get('/create', [\App\Http\Controllers\statusController::class, 'create'])->name('status.create');
        Route::post('/store', [\App\Http\Controllers\statusController::class, 'store'])->name('status.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\statusController::class, 'edit'])->name('status.edit');
        Route::put('/{id}', [\App\Http\Controllers\statusController::class, 'update'])->name('status.update');
        Route::delete('/{id}', [\App\Http\Controllers\statusController::class, 'destroy'])->name('status.destroy');
    });

    // Unit Routes
    Route::prefix('unit')->group(function () {
        Route::get('/', [\App\Http\Controllers\unitController::class, 'index'])->name('unit.index');
        Route::get('/create', [\App\Http\Controllers\unitController::class, 'create'])->name('unit.create');
        Route::post('/store', [\App\Http\Controllers\unitController::class, 'store'])->name('unit.store');
        Route::get('/{unit}/edit', [\App\Http\Controllers\unitController::class, 'edit'])->name('unit.edit');
        Route::put('/{unit}', [\App\Http\Controllers\unitController::class, 'update'])->name('unit.update');
        Route::delete('/{unit}', [\App\Http\Controllers\unitController::class, 'destroy'])->name('unit.destroy');
    });

    // DUnit Routes
    Route::prefix('dunit')->group(function () {
        Route::get('/', [\App\Http\Controllers\dunitController::class, 'index'])->name('dunit.index');
        Route::get('/create', [\App\Http\Controllers\dunitController::class, 'create'])->name('dunit.create');
        Route::post('/store', [\App\Http\Controllers\dunitController::class, 'store'])->name('dunit.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\dunitController::class, 'edit'])->name('dunit.edit');
        Route::put('/{id}', [\App\Http\Controllers\dunitController::class, 'update'])->name('dunit.update');
        Route::delete('/{id}', [\App\Http\Controllers\dunitController::class, 'destroy'])->name('dunit.destroy');
    });

    // Product Routes
    Route::prefix('products')->group(function () {
        Route::get('/', [\App\Http\Controllers\productController::class, 'index'])->name('products.index');
        Route::get('/create', [\App\Http\Controllers\productController::class, 'create'])->name('products.create');
        Route::post('/store', [\App\Http\Controllers\productController::class, 'store'])->name('products.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\productController::class, 'edit'])->name('products.edit');
        Route::put('/{id}', [\App\Http\Controllers\productController::class, 'update'])->name('products.update');
        Route::delete('/{id}', [\App\Http\Controllers\productController::class, 'destroy'])->name('products.destroy');
    });
    
    // Supplier Routes
    Route::prefix('supplier')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    });

    Route::get('/home', [OrderController::class, 'new'])->name('home');

    // Order Routes
    Route::prefix('order')->group(function () {
        // This is the main page for ALL orders
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        
        // Create new orders
        Route::get('/new', [OrderController::class, 'new'])->name('order.new');
        Route::post('/create', [OrderController::class, 'create'])->name('order.create');

        // View single order (PO)
        Route::get('/{id}', [OrderController::class, 'show'])->name('order.show');

        // *** ADD THIS ROUTE FOR THE MODAL ***
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('order.edit');
        
        // *** ADD THIS ROUTE FOR THE MODAL SUBMISSION ***
        Route::put('/{id}', [OrderController::class, 'update'])->name('order.update');

        // *** ADD THIS ROUTE FOR THE DUPLICATE BUTTON ***
        Route::post('/{id}/duplicate', [OrderController::class, 'duplicate'])->name('order.duplicate');

        // Delete order
        Route::delete('/{id}', [OrderController::class, 'delete'])->name('order.delete');
        
        // Inline-edit form submission
        Route::post('/update-status/', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
        Route::post('/bulk-edit', [OrderController::class, 'showBulkEditForm'])->name('order.bulkEditForm');
        // This route *saves* the changes from the bulk edit page
        Route::post('/bulk-update', [OrderController::class, 'bulkUpdate'])->name('order.bulkUpdate');
        Route::post('/bulk-po', [OrderController::class, 'bulkPo'])->name('order.bulkPo');
    });

    // DataTables AJAX route
    Route::get('orders/data', [OrderController::class, 'getOrdersData'])->name('order.data');

    // Report Routes
    Route::prefix('reports')->group(function () {
        Route::get('/buyer-report', [ReportController::class, 'buyerReport'])->name('reports.buyer');
        Route::post('/generate-buyer-report', [ReportController::class, 'generateBuyerReport'])->name('reports.generateBuyerReport');
        Route::post('/generate-supplier-report', [ReportController::class, 'generateSupplierReport'])->name('reports.generateSupplierReport');
    });

    Route::get('/info', function() { phpinfo(); });
    
    // Import Routes
    Route::prefix('import')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('import.index');
        Route::post('/upload', [ImportController::class, 'upload'])->name('import.upload');
});
//});
