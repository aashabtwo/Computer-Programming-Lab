<?php

namespace App\Http\Controllers\Submit;

use App\Http\Controllers\Controller;
use App\Models\CodeSubmission;
use App\Models\Problem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
         * Define the file name and the file storage path
         * insert problem_id, userid, code_path. Save the submission data later
         * use the $id to fetch problems iter_num and title
         * Remove the white spaces from the title and use the iternum
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
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:c' // specifying only .c files are accepted
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request. Try submitting again'
            ], 400);
        }
        if ($request->file()) {
            $num = 0;
            $userId = $request->user()->id;
            $submittedCodeName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('submissions', $submittedCodeName, 'public');
            $problem = Problem::where('id', $id)->get()->first();
            $problem_title = $problem->title;
            $iterations = $problem->iter_num;
            if ($iterations == 'Variable') {
                //
            }
            // else
            $title = str_replace(' ', '', $problem_title);
            $submission = new CodeSubmission();
            $submission->problem_id = $id;
            $submission->user_id = $userId;
            $submission->code_path = '/storage/'.$filePath;


            $empty_array = array();
            
            
            // get directory path compile code, then run code against test cases
            $direct = dirname(dirname(dirname(dirname(__DIR__))));
            $submission_directory = $direct."/storage/app/public/submissions/";
            $solution_directory = "solutions/";
            $compile_command = 'gcc -lm '.$submission_directory. ''.$submittedCodeName;
            exec($compile_command); // make sure to implement exception handling later
            $second_direct = dirname(__DIR__);
            // $path = $second_direct.'/Submit/input1.txt';
            $solution_path = $second_direct.'/Submit/solutions/'. $title;
            $solution_file = fopen($solution_path . '/solution.txt', 'r');
            $solution_string = fread($solution_file, filesize($solution_path . "/solution.txt"));
            $solution = explode("\n", $solution_string);
            fclose($solution_file);
            
            $j = 0; // iteration
            for ($i = 0; $i < 5; $i++) {    // 5 test cases
                $path = $solution_path . "/input" . strval($i) . '.txt';
                $execute_command = './a.out < ' . $path;
                $code = shell_exec($execute_command);
                $splitted = explode("\n", $code);
                $output_length = count($splitted);
                // for a in output_length
                $b = $j;
                for ($a = 0; $a < $output_length; $a++) {
                    if ($splitted[$a] != $solution[$b]) {
                        $num = 1;
                        $message = "Failed";
                        $empty_array[] = $message;
                        $empty_array[] = false;
                        break;
                    }
                    else {
                        $message = "Passed";
                        $empty_array[] = $message;
                    }
                    $b += 1;
                }
                /*
                if ($splitted[0] == $solution[$j] && $splitted[1] == $solution[$j+1] ) {
                    $message = "Passed";
                    $empty_array[] = $message;
                } 
                else {
                    $num = 1;
                    $message = "Failed";
                    $empty_array[] = $message;
                    $empty_array[] = false;
                    break;
                }
                */
                $j = $j + $iterations;
            }

            if ($num == 0) {
                $submission->passed = true;
            }
            else {
                $submission->passed = false;
            }
            
            $submission->save();
           
            return response()->json([
                'satus_code' => 201,
                'message' => 'Code Submitted',
                'message' => $empty_array

            ]);

        }
    }
}
