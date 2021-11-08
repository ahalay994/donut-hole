<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\DepartmentController;
use \App\Http\Controllers\Api\MemberController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/', function () {
    return response()->json('API.');
});

Route::prefix('/department')->name('api.department.')->group(function () {
    Route::get('/', [DepartmentController::class, 'get'])->name('get');
    Route::post('/create', [DepartmentController::class, 'store'])->name('create');
    Route::put('/{id}/update', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [DepartmentController::class, 'delete'])->name('delete');
});

Route::prefix('/member')->name('api.member.')->group(function () {
    Route::get('/', [MemberController::class, 'get'])->name('get');
    Route::post('/create', [MemberController::class, 'store'])->name('create');
    Route::put('/{id}/update', [MemberController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [MemberController::class, 'delete'])->name('delete');
});
