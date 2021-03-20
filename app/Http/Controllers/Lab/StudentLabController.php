<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission;
use App\Models\LabAssignment;
use App\Models\LabStudent;
use Illuminate\Http\Request;

class StudentLabController extends Controller
{
    // method to display the labs the student is registered in
    public function labs(Request $request) {
        /**
         * show only the labs the student registered in
         * pull the user id to query LabStudent model where
         * this student is registered in
         */
        $user_id = $request->user()->id;
        $labs = LabStudent::where('user_id', $user_id)->get();
        if ($labs) {
            return response()->json([
                'labs' => $labs,
                'user_id' => $user_id
            ]);
        }
        else {
            return response()->json([
                'message' => 'You have not joined any labs yet'
            ], 403);
        }
    }

    // method to get one lab
    public function lab(Request $request, $id) {
        $user_id = $request->user()->id;
        // this will show the lab with the given id and student's user_id
        $lab = LabStudent::where('id', $id)->where('user_id', $user_id)->get()->toJson(JSON_PRETTY_PRINT); 
        if ($lab) {
            return response($lab, 200);
        }
        else {
            return response()->json([
                'message' => 'Lab does not exist'
            ], 404);
        }
    }

    // method to check all given assignments
    public function labAssignments(Request $request) {
        /**
         * REMINDER: the route that this method belongs must pass two middlewares -> "auth:api" and "labstudent"
         * query all the given assignments with this lab id
         */
        $lab_id = $request->route('id');
        $assignments = LabAssignment::where('lab_id', $lab_id)->where('head', $request->route('lab_no'))->get()->toJson(JSON_PRETTY_PRINT);
        return response($assignments, 200);
    }
    // method to check one assignment
    public function oneLabAssignment(Request $request) {
        /**
         * REMINDER: the route that this method belongs must pass two middlewares -> "auth:api" and "labstudent"
         * query one given assignment bearing this lab id
         */
        $lab_id = $request->route('id');
        $assignment_id = $request->route('assignment_id');
        $assignment = LabAssignment::where('lab_id', $lab_id)->where('id', $assignment_id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($assignment, 200);
    }
    // method to show accepted or rejected submissions
    public function accepts(Request $request) {
        $bool = $request->route('bool');
        if ($bool == 'accept') {
            $submission = AssignmentSubmission::where('user_id', $request->user()->id)->where('approved', true)->get()->toJson(JSON_PRETTY_PRINT);
        }
        elseif ($bool == 'rejects') {
            $submission = AssignmentSubmission::where('user_id', $request->user()->id)->where('reviewed', true)->where('approved', false)->get()->toJson(JSON_PRETTY_PRINT);
        }
        return response($submission, 200);
    }
    // method to show one accepted/rejected submission
    public function oneSubmission(Request $request) {
        $submission_id = $request->route('s_id');
        $submission = AssignmentSubmission::where('id', $submission_id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($submission, 200);
    }


    
}
