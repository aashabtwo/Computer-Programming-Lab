<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
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
        * take the user's name from request
        */
        $lab_problem = LabProblem::where('id', $id)->get()->first();
        $assignment = new LabAssignment();
        $assignment->title = $lab_problem->title;
        $assignment->descriptin = $lab_problem->description;
        $assignment->objective = $lab_problem->objective;
        $assignment->task = $lab_problem->task;
        $assignment->input_content = $lab_problem->input_content;
        $assignment->output_content = $lab_problem->output_content;
        $assignment->iter_num = $lab_problem->iter_num;
        $assignment->marks = $lab_problem->marks;
        


    }
}
