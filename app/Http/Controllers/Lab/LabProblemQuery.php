<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabProblem;
use App\Models\Problem;
use Illuminate\Http\Request;

class LabProblemQuery extends Controller
{
    // get all lab problems
    public function getAllProblems() {
        $problems = LabProblem::get()->toJson(JSON_PRETTY_PRINT);
        return response($problems, 200);
    }
    // get single lab problem
    public function getOneProblem($id) {
        $problem = Problem::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($problem, 200);
    }
}
