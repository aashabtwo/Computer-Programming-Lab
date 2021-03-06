<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUpload;
use App\Http\Controllers\Practice\ProblemCreationController;
use App\Http\Controllers\Practice\ProblemQuery;
use App\Http\Controllers\Submit\CodeSubmit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::get('/login', function() {
    return response()->json([
        "Message" => "LOGIN FIRST"
    ]);
})->name('login');


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

// get one problem
Route::get('problems/{id}', [ProblemQuery::class, 'getOneProblem']);

// submit problem
Route::post('problems/{id}', [CodeSubmit::class, 'submit'])->middleware('auth:api');

// just for testing
Route::get('/some/definite/user', function(Request $request) {
    return $request->user()->id;
})->middleware(('auth:api'));



// storing file
Route::get('/write', function() {
    Storage::disk('local')->put('example.txt', 'some contents');
});


// getting the contents of a file in raw string
Route::get('/getfile', function() {
    $content = Storage::disk('local')->get('example.txt');
    return response()->json([
        "content" => $content
    ]);
});

// file upload route
Route::post('/uploadfile', [FileUpload::class, 'fileUpload']);


// url params test
Route::get('/value/{code}', function(Request $request) {
    $value = $request->route('code');
    return response()->json([
        'value' => $value,
    ]);
});










/**
 * "email": "snape@gmail.com","password": "testing111"
  
 */
/**
 * "name": "Aashab Tajwar",
*	"email": "tajwar@gmail.com",
*	"password": "testing111",
*	"position": "Student"
 */