<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabAssignment;
use App\Models\LabProblem;
use Illuminate\Http\Request;

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
}
