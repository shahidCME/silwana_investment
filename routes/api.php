<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\SalesPersonController;
use App\Http\Controllers\api\SchemaController;

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
Route::post('login',[LoginController::class,'index']);
Route::get('/logout',[LoginController::class,'logout']);
Route::post('/forgetPassword',[LoginController::class,'forgetPassword']);
Route::post('/getProfile',[LoginController::class,'getProfile']);
Route::post('/updateProfile',[LoginController::class,'updateProfile']);



Route::post('/getUsers', [UserController::class, 'index']);
Route::post('/addUser', [UserController::class, 'addUser']);
Route::post('/editUser', [UserController::class, 'edit']);
Route::post('/deleteUser', [UserController::class, 'delete']);
Route::post('/updateUser', [UserController::class, 'update']);


Route::post('getSalesperson',[SalesPersonController::class,'index']);
Route::post('addSalesPerson',[SalesPersonController::class,'addSalesPerson']);
Route::post('editSalesPerson',[SalesPersonController::class,'editSalesPerson']);
Route::post('updateRecord',[SalesPersonController::class,'updateRecord']);
Route::post('deleteRecord',[SalesPersonController::class,'deleteRecord']);


Route::post('getSchema',[SchemaController::class,'index']);
Route::post('addSchema',[SchemaController::class,'addSchema']);
Route::post('editSchema',[SchemaController::class,'editSchema']);
Route::post('updateSchema',[SchemaController::class,'updateSchema']);
Route::post('deleteSchema',[SchemaController::class,'deleteSchema']);

// Route::group(['middleware' => ['auth:sanctum','abilities:admin']], function () {
//     Route::get('/getUsers', [UserController::class, 'index']);
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});