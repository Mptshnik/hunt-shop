<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('guest')->group(function (){
    Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);


});

Route::middleware('auth:sanctum')->group(function ()
{
    Route::get('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);

    Route::get('post/all', [\App\Http\Controllers\PostController::class, 'getAll']);

    Route::group(['prefix' => '/employee'], function (){
        Route::post('/create', [\App\Http\Controllers\EmployeeController::class, 'store']);
        Route::post('/update/{id}',[\App\Http\Controllers\EmployeeController::class, 'update']);
        Route::post('/delete/{id}',[\App\Http\Controllers\EmployeeController::class, 'delete']);
        Route::get('/all', [\App\Http\Controllers\EmployeeController::class, 'getAll']);
        Route::get('/{id}',[\App\Http\Controllers\EmployeeController::class, 'getOne']);
    });

    Route::group(['prefix' => '/user'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\UserController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\UserController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\UserController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\UserController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\UserController::class, 'getOne']);
    });
    Route::get('/authorized-user', [\App\Http\Controllers\UserController::class, 'getAuthorizedUser']);

    Route::group(['prefix' => 'post'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\PostController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\PostController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\PostController::class, 'update']);

        Route::get('/{id}', [\App\Http\Controllers\PostController::class, 'getOne']);
    });

    Route::group(['prefix' => '/client'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\ClientController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\ClientController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\ClientController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\ClientController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\ClientController::class, 'getOne']);
    });

    Route::group(['prefix' => '/provider'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\ProviderController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\ProviderController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\ProviderController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\ProviderController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\ProviderController::class, 'getOne']);
    });

    Route::group(['prefix' => '/seller'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\SellerController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\SellerController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\SellerController::class, 'update']);
        Route::get('/{id}', [\App\Http\Controllers\SellerController::class, 'getOne']);
    });

    Route::group(['prefix' => '/promotion'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\PromotionController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\PromotionController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\PromotionController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\PromotionController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\PromotionController::class, 'getOne']);
    });

    Route::group(['prefix' => '/purchase-application'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\PurchaseApplicationController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\PurchaseApplicationController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\PurchaseApplicationController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\PurchaseApplicationController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\PurchaseApplicationController::class, 'getOne']);
    });

    Route::group(['prefix' => '/item-invoice'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\ItemInvoiceController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\ItemInvoiceController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\ItemInvoiceController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\ItemInvoiceController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\ItemInvoiceController::class, 'getOne']);
    });

    Route::group(['prefix' => '/item-category'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\ItemCategoryController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\ItemCategoryController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\ItemCategoryController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\ItemCategoryController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\ItemCategoryController::class, 'getOne']);
    });

    Route::group(['prefix' => '/item'], function ()
    {
        Route::post('/create',[\App\Http\Controllers\ItemController::class, 'store']);
        Route::post('/delete/{id}',[\App\Http\Controllers\ItemController::class, 'delete']);
        Route::post('/update/{id}',[\App\Http\Controllers\ItemController::class, 'update']);
        Route::get('/all', [\App\Http\Controllers\ItemController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\ItemController::class, 'getOne']);
    });

    Route::group(['prefix' => '/order'], function (){
        Route::post('/confirm', [\App\Http\Controllers\OrderController::class, 'confirm']);
        Route::get('/all', [\App\Http\Controllers\OrderController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\OrderController::class, 'getOne']);
    });

    Route::group(['prefix' => '/cart'], function ()
    {
        Route::post('/add-item/{id}', [\App\Http\Controllers\CartController::class, 'addToCart']);
        Route::post('/remove-item/{id}', [\App\Http\Controllers\CartController::class, 'removeFromCart']);
        Route::post('/remove-all', [\App\Http\Controllers\CartController::class, 'removeAllFromCart']);
        Route::get('/get', [\App\Http\Controllers\CartController::class, 'index']);
    });

    Route::group(['prefix' => '/invoice'], function ()
    {
        Route::get('/all', [\App\Http\Controllers\InvoiceController::class, 'getAll']);
        Route::get('/{id}', [\App\Http\Controllers\InvoiceController::class, 'getOne']);
    });
});
