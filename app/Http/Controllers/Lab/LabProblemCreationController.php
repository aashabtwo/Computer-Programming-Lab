<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabProblem;
use Illuminate\Http\Request;

class LabProblemCreationController extends Controller
{
    //
    public function create(Request $request) {
        /**
         * create the problems
         */
        $problem = new LabProblem();
        //$problem->head = $request->head;
        $problem->title = $request->title;
        $problem->description = $request->description;
        $problem->objective = $request->objective;
        $problem->task = $request->task;
        $problem->input_content = $request->inputContent;
        $problem->output_content = $request->outputContent;
        $problem->iter_num = $request->iter_num;
        $problem->save();

        return response()->json([
            'message' => "New Problem created"
        ], 201);
    }
}
