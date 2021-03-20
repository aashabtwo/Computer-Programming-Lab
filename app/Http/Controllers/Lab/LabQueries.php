<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabProblem;
use App\Models\LabTeacher;
use Illuminate\Http\Request;

class LabQueries extends Controller
{
    // query all labs
    public function getAllLabs() {
        $labs = Lab::get()->toJson(JSON_PRETTY_PRINT);
        return response($labs, 200);
    }
    // query lab teachers
    public function getAllLabTeachers() {
        $lab_teachers = LabTeacher::get()->toJson(JSON_PRETTY_PRINT);
        return response($lab_teachers, 200);
    }
    
    // method to show all labs the teacher is part of/labs that the teacher registered
    // the authenticated user must be a teacher
    public function labs(Request $request) {
        $teacher_id = $request->user()->id;
        $labs = LabTeacher::where('user_id', $teacher_id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($labs, 200);

    }
    // method to show one of the labs the teacher register
    // the authenticated user must be a teacher
    public function oneLab(Request $request, $id) {
        $teacher_id = $request->user()->id;
        $lab = LabTeacher::where('user_id', $teacher_id)->where('lab_id', $id)->get()->first();
        if ($lab) {
        return response($lab, 200);
        }
        else {
            return response()->json([
                'message' => "Lab not found"
            ], 404);
        }
    }
    // method to show lab problems
    public function problems (Request $request) {
        
        $problems = LabProblem::where('head', $request->route('lab_no'))->get()->toJson(JSON_PRETTY_PRINT);
        return response($problems, 200);
        
    }

    // method to show one lab problem
    public function problem(Request $request, $id) {
        $problem_id = $request->route('p_id');
        $problem = LabProblem::where('id', $problem_id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($problem, 200);
    }
}
