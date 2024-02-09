<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\apiController;
use App\Http\Controllers\Api\newStudentController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/test',function(){
    p("Keep it up");
});

Route::post('api/store','App\Http\Controllers\Api\apiController@store');

Route::get('users/get/{greensign}',[apiController::class,'index']);
Route::get('user/{id}',[apiController::class,'show']);
Route::delete('del/{id}',[apiController::class,'destroy']);
Route::put('update/{id}',[apiController::class,'update']);
Route::patch('password/{id}',[apiController::class,'NewPassword']);  //for password upadte or single update 



//Api with token
//register for user 

Route::post('user-register',[newStudentController::class,'register']);

//login for user

// Route::post('login/{id}',[newStudentController::class,'login']);

// now i want the fetch the data 
//this is find the data with open api 

Route::get('student/{id}',[newStudentController::class,'fetchdata']);            

//now we want only token login with same last route with middleware auth is impnow 

Route::middleware('auth:api')->group(function(){
    Route::get('student/{id}',[newStudentController::class,'fetchdata']);  
});



