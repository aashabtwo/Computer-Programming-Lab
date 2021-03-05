<?php

namespace App\Http\Controllers\Submit;

use App\Http\Controllers\Controller;
use App\Models\CodeSubmission;
use Illuminate\Http\Request;

class CodeSubmit extends Controller
{
    // method to handle code submission
    public function submit(Request $request, $id) {
        /**
         * Validate request body
         * Only allow .c extentsion files
         * use the $id parameter to get the problem's title
         * check if the request object contains any files
         * get the user id from the token
         * Set the file name and define the file storage path
         * insert and save the submission data
         * Define a variable (num) and set it to 0
         * Initiate an empty array (this is where test case results will be saved)
         * Now attempt to compile the code
         * Handle exception if there is any
         * otherwise procede to execute the code against test cases
         * Handle execution exception if there is any
         * Otherwise, run code against test cases (in a loop)
         * Push the test case results in the array
         * If one test case fails, set num = 1, append a 'false' bool at the end of the array
         * Else, keep num = 0, and when the loop ends, append a 'true' bool at the end and set passed = true
         * return the array as the response
         */
        if ($request->file()) {
            $userId = $request->user()->id;
            $submittedCodeName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('submissions', $submittedCodeName, 'public');
            
            $submission = new CodeSubmission();
            $submission->problem_id = $id;
            $submission->user_id = $userId;
            $submission->code_path = '/storage/'.$filePath;
            $submission->passed = false;
            $submission->save();
            
            return response()->json([
                'satus_code' => 201,
                'message' => 'Code Submitted'
            ]);

        }
    }
}
