<?php

namespace App\Http\Controllers\Practice;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemQuery extends Controller
{
    //
    public function getAllProblems() {
        $problems = Problem::get()->toJson(JSON_PRETTY_PRINT);
        return response($problems, 200);
    }
}
