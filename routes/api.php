<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

/* Authentication */
Route::post('/register',[AuthController::class,'register']); //University Staff Register - endpoint : localhost:8000/api/register
Route::post('/login',[AuthController::class,'login']); //University Staff Login - endpoint : localhost:8000/api/login
Route::get('/login',[AuthController::class,'login'])->name('login');
/* End Authentication */


/* below details - must include in header to access secured route */
//no   //key                //value
//1    //Accept             //application/json
//2    //Authorization      //Bearer [token]

/* list(GROUP) of secured route - need authenticated first */
Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('/test', function(){ return response(['test' => 'ok'], 200);}); //for testing secured link


});
/* end secured route */