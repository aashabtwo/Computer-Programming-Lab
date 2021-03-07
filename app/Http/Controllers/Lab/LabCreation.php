<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabTeacher;
use Illuminate\Http\Request;

class LabCreation extends Controller
{
    // method to create lab
    public function createLab(Request $request) {
        /**
         * CREATE LAB AND LAB TEACHER
         * validate and sanitize request body
         * pull user id and name from request body
         * insert data
         */
        // lab_name
        // lab_category
        // department
        // for teachers -> user_id, teacher_name, lab_id, lab_name
        $lab = new Lab();
        $lab->lab_name = $request->lab_name;
        $lab->lab_category = $request->lab_category;
        $lab->department = $request->department;
        $lab->save();

        $lab_teacher = new LabTeacher();
        $lab_teacher->lab_id = $lab->id;
        $lab_teacher->lab_name = $request->lab_name;
        $lab_teacher->user_id = $request->user()->id;
        $lab_teacher->teacher_name = $request->user()->name;
        $lab_teacher->save();

        return response()->json([
            'status_code' => 201,
            'message' => 'Lab created'
        ]);


    }
}
