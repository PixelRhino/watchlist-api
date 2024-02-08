<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\WatchlistItemController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout',   [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::get('me',        [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api'
], function () {
    Route::get('watchlists', [WatchlistController::class, 'watchlists']);
    Route::get('watchlists/{watchlist_id}', [WatchlistController::class, 'watchlist']);
    Route::post('watchlists', [WatchlistController::class, 'store']);
    Route::post('watchlists/{watchlist_id}/update', [WatchlistController::class, 'update']);
    Route::post('watchlists/{watchlist_id}/delete', [WatchlistController::class, 'delete']);

    Route::get('watchlists/{watchlist_id}/items', [WatchlistItemController::class, 'items']);
    Route::post('watchlists/{watchlist_id}/items', [WatchlistItemController::class, 'store']);
    Route::get('watchlist-items/{item_id}', [WatchlistItemController::class, 'item']);
    Route::post('watchlist-items/{item_id}/update', [WatchlistItemController::class, 'update']);
    Route::post('watchlist-items/{item_id}/delete', [WatchlistItemController::class, 'delete']);
});



