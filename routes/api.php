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

Route::get('/department', [\App\Http\Controllers\Api\DepartmentController::class, 'get']);
Route::post('/department/create', [\App\Http\Controllers\Api\DepartmentController::class, 'store']);
Route::put('/department/{id}/update', [\App\Http\Controllers\Api\DepartmentController::class, 'update']);
Route::delete('/department/{id}/delete', [\App\Http\Controllers\Api\DepartmentController::class, 'delete']);

Route::get('/member', [\App\Http\Controllers\Api\MemberController::class, 'get']);
Route::post('/member/create', [\App\Http\Controllers\Api\MemberController::class, 'store']);
Route::put('/member/{id}/update', [\App\Http\Controllers\Api\MemberController::class, 'update']);
Route::delete('/member/{id}/delete', [\App\Http\Controllers\Api\MemberController::class, 'delete']);
