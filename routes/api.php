<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Practice\ProblemCreationController;
use App\Http\Controllers\Practice\ProblemQuery;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('createuser', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/info', function() {
    return response()->json([
        "message"=>"Message only for authenticated users"
    ]);
})->middleware('auth:api');
Route::post('createproblem', [ProblemCreationController::class, 'create']);
Route::get('problems', [ProblemQuery::class, 'getAllProblems']);
/**
 * "email": "snape@gmail.com","password": "testing111"
  
 */
/**
 * "name": "Aashab Tajwar",
*	"email": "tajwar@gmail.com",
*	"password": "testing111",
*	"position": "Student"
 */