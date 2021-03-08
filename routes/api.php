<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUpload;
use App\Http\Controllers\Lab\LabCreation;
use App\Http\Controllers\Lab\LabProblemCreationController;
use App\Http\Controllers\Lab\LabProblemQuery;
use App\Http\Controllers\Lab\LabQueries;
use App\Http\Controllers\Lab\StudentJoin;
use App\Http\Controllers\Practice\ProblemCreationController;
use App\Http\Controllers\Practice\ProblemQuery;
use App\Http\Controllers\Submit\CodeSubmit;
use App\Models\Lab;
use App\Models\LabProblem;
use App\Models\User;
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

// create practice problems
Route::post('createproblem', [ProblemCreationController::class, 'create']);
Route::get('problems', [ProblemQuery::class, 'getAllProblems']);

// create lab problem
Route::post('createlabproblems', [LabProblemCreationController::class, 'create']);


// get one problem
Route::get('problems/{id}', [ProblemQuery::class, 'getOneProblem']);

// submit problem
Route::post('problems/{id}', [CodeSubmit::class, 'submit'])->middleware('auth:api')->middleware('checkuser');


// get all lab problems
Route::get('lab/problems', [LabProblemQuery::class, 'getAllProblems']);
// get one lab problem
Route::get('lab/problems/{id}', [LabProblemQuery::class, 'getOneProblem']);

// create lab
Route::post('createlab', [LabCreation::class, 'createLab'])
    ->middleware('auth:api')
    ->middleware('checkuser');

// get labs
Route::get('labs-all', [LabQueries::class, 'getAllLabs']);
// get lab teachers
Route::get('labteachers', [LabQueries::class, 'getAllLabTeachers']);

// register student to a lab
Route::post('lab/join/{id}', [StudentJoin::class, 'registerStudent'])->middleware('auth:api');

// get the labs created by the teacher
Route::get('labs', [LabQueries::class, 'labs'])->middleware('auth:api')->middleware('checkuser');
// get one lab created by the teacher
Route::get('labs/{id}', [LabQueries::class, 'oneLab'])->middleware('auth:api')->middleware('checkuser');


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

// url params test 2
Route::get('value/{id}/problems', function(Request $request) {
    $value = $request->route('id');
    return response()->json([
        'value' => $value
    ]);
});

// query test
Route::get('query', function () {
    $user = User::where('id', 1)->where('email', 'aashab@gmail.com')->get()->first();
    return response()->json([
        'user' => $user
    ]);
});

// check middleware
Route::get('/teacher', function(Request $request) {
    return response()->json([
        "message" => "Hello Teacher!"
    ]);
})->middleware('auth:api')->middleware('checkuser');








/**
 * "email": "snape@gmail.com","password": "testing111"
  
 */
/**
 * "name": "Aashab Tajwar",
*	"email": "tajwar@gmail.com",
*	"password": "testing111",
*	"position": "Student"
 */

/*
{
	
	"email":"snape@gmail.com",
	"name": "Professor Snape",
	"position": "Teacher",
	"password":"testing111"
}
*/