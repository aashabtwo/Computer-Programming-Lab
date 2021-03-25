<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission as ModelsAssignmentSubmission;
use App\Models\LabAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\exception_for;

class AssignmentSubmission extends Controller
{
    /**
     *      CODE WILL BE DRASTICALLY CHANGED LATER
     *      I JUST WANTED TO HAVE THE BASIC FUNCTIONALITIES TO WORK
     * 
     */


    // method to handle assingment submission
    public function submit(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:c'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request. Try submitting again'
            ], 400);
        }

        if ($request->file()) {
            $submissionName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('assignment_submissions', $submissionName, 'public');
            $direct = dirname(__DIR__);
            $root_directory = dirname(dirname(dirname(dirname(__DIR__))));
            $solution_directory = $direct.'/Submit/solutions/';
            $submissions_directory = $root_directory . "/storage/app/public/assignment_submissions/";
            $user_id = $request->user()->id;
            $lab_id = $request->route('id');
            $assignment_id = $request->route('assignment_id');
            
            // get the title and iter_num
            $assignment = LabAssignment::where('id', $assignment_id)->get()->first();
            $title = $assignment->title;
            $title_trimmed = str_replace(' ', '', $title);
            $iterations = $assignment->iter_num;
            
            $assignment_submission = new ModelsAssignmentSubmission();
            $assignment_submission->lab_assignment_id = $assignment_id;
            $assignment_submission->submitted_by = $request->user()->name;
            $assignment_submission->lab_id = $lab_id;
            $assignment_submission->user_id = $user_id;
            $assignment_submission->code_path = '/storage/'. $filePath;
            // $assignment_submission->passed = false;
            $assignment_submission->reviewed = false;
            $assignment_submission->approved = false;
            $assignment_submission->passed = false;
            $assignment_submission->remarks = 'none';

            // execute and run the code here
                /**
                 * fetch title
                 * use the title to load solution
                 * run test cases against solution lines
                 */
            $compile_command = 'gcc -lm ' . $submissions_directory . '' . $submissionName . ' ' . '2>&1';
            // handle compilation exception
            $compile = shell_exec($compile_command);
            if ($compile) {
                // send the error
                return response()->json([
                    'message' => 'Compilation Error',
                    'error' => $compile
                ]);
            }
            // else
            if ($title_trimmed == 'HelloWorldinC') {
                // execute code without inputs
                // handling runtime exception
                $code = exec('./a.out');
                if (!$code) {
                    // will be null if an exception occurs
                    // capture the runtime error
                    // return response
                    $runtime_error = shell_exec('./a.out 2>&1');
                    return response()->json([
                        'message' => 'Runtime Error',
                        'error' => $runtime_error,
                    ]);
                }
                if ($code == 'Hello, World!') {
                    $assignment_submission->passed = true;
                }
                else {
                    $assignment_submission->passed = false;
                }
            }
            else {
                // execute with inputs
                $iteration = (int)$iterations;
                $num = 0;
                // load solution, 

                $solution_path = $solution_directory . '' . $title_trimmed;
                $solution_file = fopen($solution_path . '/solution.txt', 'r');
                $solution_string = fread($solution_file, filesize($solution_path . "/solution.txt"));
                $solution = explode("\n", $solution_string);
                
                // execute code
                $input_path = $solution_path . '/' . 'input0.txt';
                $execute_command = './a.out < ' . $input_path;
                $code = shell_exec($execute_command);
                // handling runtime exception
                if (!$code) {
                    // $code is null if a runtime exception occurs
                    // capture the runtime error
                    // return response
                    $runtime_error = shell_exec('./a.out 2>&1');
                    return response()->json([
                        'message' => 'Runtime Error',
                        'error' => $runtime_error,
                    ]);
                }
                // else
                $output = explode("\n", $code);
                for ($i = 0; $i < count($output); $i++) {
                    if ($output[$i] != $solution[$i]) {
                        $assignment_submission->passed = false;
                        $assignment_submission->save();
                        return response()->json([
                            'message' => 'Failed! Try submitting again'
                        ], 200);
                        break;
                    }
                }
                $assignment_submission->passed = true;
                $assignment_submission->save();
                return response()->json([
                    'message' => 'Success! Your submission is accepted. Your instructor will now review your code'
                ], 200);
                
                }

            }
        
        }
    }

