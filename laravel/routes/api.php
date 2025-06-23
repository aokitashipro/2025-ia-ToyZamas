<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Owner\ToyController as OwnerToyController;
use App\Http\Controllers\Api\Owner\StockController as OwnerStockController;
use App\Http\Controllers\Api\Owner\HistoryController as OwnerHistoryController;
use App\Http\Controllers\Api\Owner\ReserveController as OwnerReserveController;
use App\Http\Controllers\Api\Owner\CategoryController as OwnerCategoryController;
use App\Http\Controllers\Api\Owner\SeriesController as OwnerSeriesController;
use App\Http\Controllers\Api\Owner\UserController as OwnerUserController;
use App\Http\Controllers\Api\Owner\SalesAnalysisController as OwnerSalesAnalysisController;

use App\Http\Controllers\Api\Toyzamas\ToyController as UserToyController;
use App\Http\Controllers\Api\Toyzamas\CartController as UserCartController;
use App\Http\Controllers\Api\Toyzamas\BuyController as UserBuyController;
use App\Http\Controllers\Api\Toyzamas\HistoryController as UserHistoryController;
use App\Http\Controllers\Api\Toyzamas\ReservesController as UserReservesController;
use App\Http\Controllers\Api\Toyzamas\FavoritesController as UserFavoritesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Owner用ルート追加
Route::apiResource('/owner/toys', OwnerToyController::class);
Route::apiResource('/owner/stocks', OwnerStockController::class);
Route::apiResource('/owner/history', OwnerHistoryController::class);
Route::apiResource('/owner/reserves', OwnerReserveController::class);
Route::apiResource('/owner/categories', OwnerCategoryController::class);
Route::apiResource('/owner/series', OwnerSeriesController::class);
Route::apiResource('/owner/users', OwnerUserController::class);
Route::apiResource('/owner/sales-analysis', OwnerSalesAnalysisController::class);

// User用ルート
Route::apiResource('/toyzamas/toys', UserToyController::class);
Route::apiResource('/toyzamas/carts', UserCartController::class);
Route::apiResource('/toyzamas/buy', UserBuyController::class);
Route::apiResource('/toyzamas/history', UserHistoryController::class);
Route::apiResource('/toyzamas/reserves', UserReservesController::class);
Route::apiResource('/toyzamas/favorites', UserFavoritesController::class);
