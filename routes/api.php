<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileUpload;
use App\Http\Controllers\Lab\Assignment;
use App\Http\Controllers\Lab\AssignmentSubmission;
use App\Http\Controllers\Lab\LabCreation;
use App\Http\Controllers\Lab\LabProblemCreationController;
use App\Http\Controllers\Lab\LabProblemQuery;
use App\Http\Controllers\Lab\LabQueries;
use App\Http\Controllers\Lab\StudentJoin;
use App\Http\Controllers\Lab\StudentLabController;
use App\Http\Controllers\Practice\ProblemCreationController;
use App\Http\Controllers\Practice\ProblemQuery;
use App\Http\Controllers\Profile\InfoController;
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

// route to see lab problems (from which the teachers can select assignments)
Route::get('labs/{id}/problems', [LabQueries::class, 'problems'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// route to see one lab problem
Route::get('labs/{id}/problems/{p_id}', [LabQueries::class, 'problem'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// Route to assign a lab problem (giving assignment)
Route::post('labs/{id}/problems/{p_id}', [Assignment::class, 'giveAssignment'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// Route to check submissions by students - Teacher's route
Route::get('labs/{id}/assignmentsubmissions', [Assignment::class, 'submissions'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// Route to check each submission
Route::get('/labs/{id}/assignmentsubmissions/{s_id}', [Assignment::class, 'submission'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// route to accept the submission
Route::put('/labs/{id}/assignmentsubmissions/{s_id}/accept', [Assignment::class, 'accept'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

Route::put('/labs/{id}/assignmentsubmissions/{s_id}/reject', [Assignment::class, 'reject'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// route to run submitted code
Route::post('/labs/{id}/assignmentsubmissions/{s_id}/runcode', [Assignment::class, 'runCode'])
    ->middleware('auth:api')
    ->middleware('checkuser')
    ->middleware('lab');

// Route to show given assignments
Route::get('lab/assignments', [Assignment::class, 'showAssignments']);


// lab dashboard for students
Route::get('lab', [StudentLabController::class, 'labs'])->middleware('auth:api');

// route to access one lab
Route::get('lab/{id}', [StudentLabController::class, 'lab'])->middleware('auth:api');

// Route to acess assignments
Route::get('lab/{id}/assignments', [StudentLabController::class, 'labAssignments'])
    ->middleware('auth:api')
    ->middleware('labstudent');

// route to access one assignment
Route::get('lab/{id}/assignments/{assignment_id}', [StudentLabController::class, 'oneLabAssignment'])
    ->middleware('auth:api')
    ->middleware('labstudent');

// route to submit assignment
Route::post('lab/{id}/assignments/{assignment_id}', [AssignmentSubmission::class, 'submit'])
    ->middleware('auth:api')
    ->middleware('labstudent');

// route for students to check submission results
Route::get('lab/{id}/results/{bool}', [StudentLabController::class, 'accepts'])
    ->middleware('auth:api')
    ->middleware('labstudent');  // if bool is true, show accepted assignments, else show rejected ones


// PROFILE BASED ROUTES
// route for users to add more info
Route::post('/profile/teaceher/setup', [InfoController::class, 'addInfoTeacher'])
    ->middleware('auth:api')
    ->middleware('checkuser');
Route::post('/profile/student/setup', [InfoController::class, 'addInfoStudent'])
    ->middleware('auth:api');


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


// check two params
Route::get('sum/{code}/and/{values}', function(Request $request) {
    $code = $request->route('code');
    $values = $request->route('values');
    return response()->json([ 
        'a' => $code,
        'b' => $values
    ]);
});

// request body line test
Route::post('/line', function(Request $request) {
    $a = explode("\r\n", $request->line);
    return response()->json([
        'res' => $a[0],
        'res2' => $a[1]
    ]);
});

