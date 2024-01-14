<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\WareHouseController;
use App\Http\Controllers\MedicineWarehouseController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoritController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\StatisticsController;


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/getid', [AuthController::class, 'getAuthenticatedUserId']);

    // get my orders admin or ph
    Route::apiResource('order' , OrderController::class);
    Route::get('/search', [MedicineController::class, 'search2']);

    Route::apiResource('medicine' , MedicineController::class);
    Route::apiResource('company' , CompanyController::class);
    Route::apiResource('category' , CategoryController::class);



    Route::group([
        'middleware' => 'pharmacist' ,
    ] , function(){

        Route::apiResource('favorit' , FavoritController::class);

    });

    Route::group([
        'middleware' => 'super_admin',
    ], function () {
        Route::post('/warehouses_sales', [StatisticsController::class, 'WareHousesSales']);
        Route::post('/medicin_sales', [StatisticsController::class, 'read_notifications']);
        Route::get('/getPharmacists', [PharmacistController::class, 'index']);

    });
    Route::group([
        'middleware' => 'admin' ,
    ] , function(){

        Route::post('/phregister', [AuthController::class, 'phregister']);
        Route::apiResource('medicine_wareHouse' , MedicineWarehouseController::class);
        Route::get('/getAmount', [MedicineWarehouseController::class, 'getAmount']);
        Route::post('/take_order', [OrderController::class, 'take_order']);
        Route::get('/getPendingOrder', [OrderController::class, 'getPendingOrder']);
        Route::delete('/admin_delete_Order/{id}', [OrderController::class, 'admin_delete_Order']);

        Route::get('/number_unread', [OrderController::class, 'number_unread']);
        Route::post('/read_notifications', [OrderController::class, 'read_notifications']);


        Route::put('/orders/{order}/status/on-the-way', [OrderController::class, 'status2on_the_way']);
        Route::put('/orders/{order}/status/completed', [OrderController::class, 'status2completed']);
        Route::put('/orders/{order}/status/to_paid', [OrderController::class, 'to_paid']);



    });



});

Route::post('/login', [AuthController::class, 'login']);


Route::get('company/{company}/medicines' , [CompanyController::class , 'medicinesByCompany']);
Route::get('category/{category}/medicines' , [CategoryController::class , 'medicinesByCategory']);

Route::post('/medicine/search' , [MedicineController::class , 'search2']) ; //if get then errore

Route::apiResource('warehouse' , WareHouseController::class);


