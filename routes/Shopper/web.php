<?php

use App\Http\Controllers\Store\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'index']);

Route::get('{uuid}', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'storeLocation']);

Route::get('{store_uuid}/{location_uuid}', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'queue']);

Route::post('limit/{store_uuid}/{location_uuid}', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'shopperLimit']);

Route::post('{store_uuid}/{location_uuid}', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'checkin']);

Route::get('{store_uuid}/{location_uuid}/{shopper_uuid}', [App\Http\Controllers\Shopper\ShopperQueueController::class, 'checkout']);


// Route::name('create')
//     ->get('/create', [StoreController::class, 'create']);

// Route::name('save')
//     ->post('/create', [StoreController::class, 'store']);

// Route::name('store')
//     ->get('/{store}', [StoreController::class, 'show']);