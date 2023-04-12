<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Product\DeleteProductController;
use App\Http\Controllers\Api\Product\IndexProductController;
use App\Http\Controllers\Api\Product\ShowProductController;
use App\Http\Controllers\Api\Product\StoreProductController;
use App\Http\Controllers\Api\Product\UpdateProductController;
use App\Http\Controllers\Api\Product\ArchivedProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', LoginController::class)->name('login');

Route::prefix('products')
    ->as('products.')
    ->middleware('auth:sanctum')
    ->group(static function (): void {
        Route::get('/', IndexProductController::class)->name('index');
        Route::get('{product}', ShowProductController::class)->name('show');
        Route::post('/', StoreProductController::class)->name('store');
        Route::put('{product}', UpdateProductController::class)->name('update');
        Route::delete('{product}', DeleteProductController::class)->name('delete');
        Route::put('{product}/archived', ArchivedProductController::class)->name('archived');
    });
