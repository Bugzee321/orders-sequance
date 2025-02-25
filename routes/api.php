
<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ApproveOrderController;

Route::prefix('orders')->group(function () {
    Route::get('/', [OrdersController::class, 'index']);
    Route::post('/', [OrdersController::class, 'store']);
    Route::get('/{order}', [OrdersController::class, 'show']);
    Route::post('/{order}/approve', [ApproveOrderController::class, '__invoke']);
});