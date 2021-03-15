<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\Lab;
use App\Models\LabAssignment;
use App\Models\LabProblem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Assignment extends Controller
{
    // method to create assignments
    // only for teachers
    public function giveAssignment(Request $request, $id) {
        /**
        * Validate and sanitize request body
        * use the $id to query LabProblem
        * use the request body to pull the lab_id
        * take the user's name from request
        */
        $lab_id = $request->route('id');
        $problem_id = $request->route('p_id');
        $lab = Lab::where('id', $lab_id)->get()->first();

        $lab_problem = LabProblem::where('id', $problem_id)->get()->first();

        $assignment = new LabAssignment();
        $assignment->title = $lab_problem->title;
        $assignment->description = $lab_problem->description;
        $assignment->objective = $lab_problem->objective;
        $assignment->task = $lab_problem->task;
        $assignment->input_content = $lab_problem->input_content;
        $assignment->output_content = $lab_problem->output_content;
        $assignment->iter_num = $lab_problem->iter_num;
        $assignment->marks = $lab_problem->marks;
        $assignment->lab_name = $lab->lab_name;
        $assignment->lab_id = $lab_id;
        $assignment->assigned_by = $request->user()->name;

        $assignment->save();

        return response()->json([
            'message' => 'Assignment has been given!'
        ], 201);
    
    }

    // method to show given assignments
    public function showAssignments(Request $request) {
        $assignments = LabAssignment::get()->toJson(JSON_PRETTY_PRINT);
        return response($assignments, 200);
    }

    // method to show student submissions
    public function submissions() {
        // THIS NEEDS TO BE CHANGED!
            /**
             * assignment submission should have 'submiited_by' field
             * should also have a 'lab_name' field
             */
        $submissions = AssignmentSubmission::get()->toJson(JSON_PRETTY_PRINT);
        return response($submissions, 200);
    }
    // method to show one submission
    public function submission(Request $request) {
        $submission = AssignmentSubmission::where('id', $request->route('s_id'))->get()->first();
        $root_directory = dirname(dirname(dirname(dirname(__DIR__))));
        $path = $submission->code_path;
        $path = str_replace('/storage/', '', $path);
        $code_path = $root_directory . '/storage/app/public/' . $path;
        $file = fopen($code_path, 'r');
        $code = fread($file, filesize($code_path));
        
        return response()->json([
            'res' => $code_path,
            'code' => $code
        ]);
    }
    
    // method to accept submission
    public function accept(Request $request) {
        $validator = Validator::make($request->all(), [
            'remarks' => 'max:150'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please keep the remarks under 100 characters'
            ], 400);
        }
        $submission = AssignmentSubmission::where('id', $request->route('s_id'))->get()->first();
        if ($request->remarks) {
            $submission->remarks = $request->remarks;
        }
        $submission->reviewed = true;
        $submission->approved = true;
        $submission->save();
        return response()->json([
            'message' => 'Submission has been accepted'
        ]);
    }
    // method to reject submissions
    public function reject(Request $request) {
        $validator = Validator::make($request->all(), [
            'remarks' => 'max:150'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please keep the remarks under 100 characters'
            ], 400);
        }
        $submission = AssignmentSubmission::where('id', $request->route('s_id'))->get()->first();
        if ($request->remarks) {
            $submission->remarks = $request->remarks;
        }
        $submission->reviewed = true;
        $submission->approved = false;
        $submission->save();
        return response()->json([
            'message' => 'Submission has been rejected'
        ]);
    }
}
