<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardBackendController;
use App\Http\Controllers\LaptopBackendController;
use App\Http\Controllers\serviceBackendController;
use App\Http\Controllers\serviceitemBackendController;
use App\Http\Controllers\UserBackendController;
use Illuminate\Support\Facades\Route;




//Route login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// ✅ Dashboard pakai middleware auth.check
Route::middleware(['auth.check'])->group(function () {
    Route::get('/', [DashboardBackendController::class, 'index']);
});



//Route Unutk User
Route::get('/user',[UserBackendController::class, 'index']);
Route::get('/user/create',[UserBackendController::class, 'create']);
Route::get('/user/edit/{id}',[UserBackendController::class, 'edit']);
Route::post('/user/store',[UserBackendController::class, 'store']);
Route::post('/user/update/{id}',[UserBackendController::class, 'update']);
Route::get('/user/delete/{id}',[UserBackendController::class, 'destroy']);
Route::post('/user/toggle/{id}',[UserBackendController::class, 'toggle']);
Route::get('/user/show/{id}',[UserBackendController::class, 'show']);
Route::get('/user/restore/{id}', [UserBackendController::class, 'restore']);
Route::get('/user/force-delete/{id}', [UserBackendController::class, 'forceDelete']);

//Route Untuk Laptop
Route::get('/laptop',[LaptopBackendController::class, 'index']);
Route::get('/laptop/create',[LaptopBackendController::class, 'create']);
Route::get('/laptop/edit/{id}',[LaptopBackendController::class, 'edit']);
Route::post('/laptop/store',[LaptopBackendController::class, 'store']);
Route::post('/laptop/update/{id}',[LaptopBackendController::class, 'update']);
Route::get('/laptop/delete/{id}',[LaptopBackendController::class, 'destroy']);
Route::post('/laptop/toggle/{id}',[LaptopBackendController::class, 'toggle']);
Route::get('/laptop/restore/{id}', [LaptopBackendController::class, 'restore']);



//Route Untuk Item
Route::get('/serviceitem', [ServiceitemBackendController::class, 'index']);
Route::get('/serviceitem/create', [ServiceitemBackendController::class, 'create']);
Route::post('/serviceitem/store', [ServiceitemBackendController::class, 'store']);
Route::get('/serviceitem/edit/{id}', [ServiceitemBackendController::class, 'edit']);
Route::post('/serviceitem/update/{id}', [ServiceitemBackendController::class, 'update']);
Route::get('/serviceitem/delete/{id}', [ServiceitemBackendController::class, 'destroy']);
Route::post('/serviceitem/toggle/{id}', [ServiceitemBackendController::class, 'toggle']);
Route::get('/serviceitem/restore/{id}', [ServiceitemBackendController::class, 'restore']);



//Route Untuk Service
Route::get('/service',[ServiceBackendController::class, 'index']);
Route::get('/service/create',[ServiceBackendController::class, 'create']);
Route::post('/service/store',[ServiceBackendController::class, 'store']);
Route::get('/service/delete/{id}', [ServiceBackendController::class, 'destroy']);
Route::get('/service/detail/{id}', [ServiceBackendController::class, 'show']);
Route::patch('/service/detail/{id}/update-payment', [ServiceBackendController::class, 'updatePayment']);
Route::get('/service/restore/{id}', [ServiceBackendController::class, 'restore']);
Route::get('/service/force-delete/{id}', [ServiceBackendController::class, 'forceDelete']);
