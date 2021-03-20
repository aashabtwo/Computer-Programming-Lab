<?php

namespace App\Http\Controllers\Practice;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemQuery extends Controller
{
    //
    public function getAllProblems(Request $request) {
        $problems = Problem::where('head', $request->route('lab_no'))->get()->toJson(JSON_PRETTY_PRINT);
        return response($problems, 200);
    }

    public function getOneProblem(Request $request) {
        /**
         * use the query id to fetch the problem
         * convert it into JSON using pretty print
         * respond with the json
         */
        $problem = Problem::where('id', $request->route('id'))->get()->toJson(JSON_PRETTY_PRINT);
        return response($problem, 200);
    }
}
//$problem = Problem::where('id', $id)->get()->first();
        //$title = $problem->title;