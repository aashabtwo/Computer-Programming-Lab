<?php

use App\Http\Controllers\AdminController;
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
use App\Http\Controllers\Password\ForgotPasswordController;
use App\Http\Controllers\Practice\ProblemCreationController;
use App\Http\Controllers\Practice\ProblemQuery;
use App\Http\Controllers\Profile\InfoController;
use App\Http\Controllers\Submit\CodeSubmit;
use App\Models\Lab;
use App\Models\LabProblem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\OAuth2\Server\RequestEvent;

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

// ADMIN PRIVILEGE ROUTES

// revoke tokens for all users
/**
 * In order to revoke all access tokens, you have to manually enter the mysql database
 * then paste the following command 
 * "UPDATE oauth_access_tokens SET revoked = 1;"
 * you can also state the condition "WHERE user_id != <admin user id>"
 * with this, all users will be logged out except for the admin
 */
// delete user

Route::get('/', function() {
    return response('Welcome');
});
Route::delete('/admin/deleteuser/{id}', [AdminController::class, 'deleteUser'])
    ->middleware('auth:api')
    ->middleware('admin');



Route::get('/login', function() {
    return response()->json([
        "Message" => "LOGIN FIRST"
    ]);
})->name('login');


// PASSWORD RESET ROUTE
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendLink'])
    ->middleware('guest');

    // reset password
Route::get('/reset-password/{token}')->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])
    ->middleware('guest')->name('password.update');







// user registration, login and logout routes
Route::post('createuser', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::get('/info', function() {
    return response()->json([
        "message"=>"Message only for authenticated users"
    ]);
})->middleware('auth:api');


// AUTHENTICATED ROUTES
Route::group(['middleware' => 'auth:api'], function() {
    
    // ADMIN ROUTES
    Route::group(['middleware' => 'admin'], function() {
            // create practice problems (only admins can do this)
        Route::post('createproblem/{lab_no}', [ProblemCreationController::class, 'create']);
        // create lab problem (admin only)
        Route::post('createlabproblem/{lab_no}', [LabProblemCreationController::class, 'create']);

    });

    // TEACHERS' ROUTES
    Route::group(['middleware' => 'checkuser'], function() {
        // submit problem
        Route::post('practice/problems/{id}', [CodeSubmit::class, 'submit']);
        
        // create lab
        Route::post('createlab', [LabCreation::class, 'createLab']);
        
        // route to see lab problems (from which the teachers can select assignments)
        Route::get('{lab_no}/labs/{id}/problems', [LabQueries::class, 'problems'])
            ->middleware('lab');
        // route to see one lab problem
        Route::get('labs/{id}/problems/{p_id}', [LabQueries::class, 'problem'])
            ->middleware('lab');

            // Route to assign a lab problem (giving assignment)
        Route::post('labs/{id}/problems/{p_id}', [Assignment::class, 'giveAssignment'])
        ->middleware('checkuser')
        ->middleware('lab');
        // Route to check submissions by students - Teacher's route
        Route::get('labs/{id}/assignmentsubmissions', [Assignment::class, 'submissions'])
            ->middleware('lab');

        // get the labs created by the teacher
        Route::get('labs', [LabQueries::class, 'labs']);

        // get one lab created by the teacher
        Route::get('labs/{id}', [LabQueries::class, 'oneLab']);

        // Route to check each submission
        Route::get('/labs/{id}/assignmentsubmissions/{s_id}', [Assignment::class, 'submission'])
            ->middleware('lab');

        // route to accept the submission
        Route::put('/labs/{id}/assignmentsubmissions/{s_id}/accept', [Assignment::class, 'accept'])
            ->middleware('lab');

        Route::put('/labs/{id}/assignmentsubmissions/{s_id}/reject', [Assignment::class, 'reject'])
            ->middleware('lab');

        // route to run submitted code
        Route::post('/labs/{id}/assignmentsubmissions/{s_id}/runcode', [Assignment::class, 'runCode'])
            ->middleware('lab');
        // PROFILE BASED ROUTES
        // route for users to add more info
        Route::post('/profile/teaceher/setup', [InfoController::class, 'addInfoTeacher']);



    });
    
    // register student to a lab
    Route::post('lab/join/{id}', [StudentJoin::class, 'registerStudent']);
        
    
    // lab dashboard for students
    Route::get('lab', [StudentLabController::class, 'labs']);

    // route to access one lab
    Route::get('lab/{id}', [StudentLabController::class, 'lab']);

    // Route to acess assignments
    Route::get('{lab_no}/lab/{id}/assignments', [StudentLabController::class, 'labAssignments'])
        ->middleware('labstudent');

    // route to access one assignment
    Route::get('lab/{id}/assignments/{assignment_id}', [StudentLabController::class, 'oneLabAssignment'])
        ->middleware('labstudent');

    // route to submit assignment
    Route::post('lab/{id}/assignments/{assignment_id}', [AssignmentSubmission::class, 'submit'])
        ->middleware('labstudent');

    // route for students to check submission results
    Route::get('lab/{id}/results/{bool}', [StudentLabController::class, 'accepts'])
        ->middleware('labstudent');  // if bool is true, show accepted assignments, else show rejected ones


    // PROFILE BASED ROUTES
    Route::post('/profile/student/setup', [InfoController::class, 'addInfoStudent']);


});
// auth:api grouping ends


// get the list of practice problems for a specific lab day
Route::get('/{lab_no}/practice/problems', [ProblemQuery::class, 'getAllProblems'])->middleware('cors');


///////
// get one problem
Route::get('practice/problems/{id}', [ProblemQuery::class, 'getOneProblem']);


// get all lab problems for a specific lab day (lab day as in lab one, lab two)
Route::get('{lab_no}/lab/problems', [LabProblemQuery::class, 'getAllProblems']);

// get one lab problem
Route::get('lab/problems/{id}', [LabProblemQuery::class, 'getOneProblem']);


// get labs
Route::get('labs-all', [LabQueries::class, 'getAllLabs']);
// get lab teachers
Route::get('labteachers', [LabQueries::class, 'getAllLabTeachers']);

// Route to show given assignments
Route::get('lab/assignments', [Assignment::class, 'showAssignments']);



// TESTING URLS
Route::group(['prefix' => 'test'], function() {
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
    
    // query param test #
    Route::get('/{code}/hey', function(Request $request) {
        $a = $request->route('code');
        return response()->json([
            'code' => $a
        ]);
    });
    // WORKS!
    
    
    // loging test
    Route::get('/log', function() {
        Log::channel('errors')->alert('HANGMAN!');
        // log messages
        // emergeney, alert, critical, error, warning, notice, debug
        // Log::channel('errors')->alert(['message' => 'asdasdasds']);
    });
    
    // password hashing test
    Route::get('/password', function() {
        $hashed_password = Hash::make('password');
        return response()->json([
            'hashed password' => $hashed_password
        ]);
    });
    
    // NOT EQUAL query test
    Route::get('/q', function() {
        $users = User::where('id', '!=', 3)->get();
        return response()->json([
            'users' => $users
        ]);
    });
    // WORKS
    
    // time test
    Route::get('/time', function() {
        $delay = json_encode(now());
        return response()->json([
            'delay' => $delay
        ]);
    });
    
    // guest middleware test
    Route::get('/lock', function() {
        return response()->json([
            'string' => 'some words'
        ]);
    })->middleware('guest');
});

