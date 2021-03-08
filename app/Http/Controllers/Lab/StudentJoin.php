<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\LabStudent;
use Illuminate\Http\Request;

class StudentJoin extends Controller
{
    // method to register students for labs
    public function registerStudent(Request $request, $id) {
        /**
         * Validate and sanitize student body
         * use the id to query the Lab
         * pull the id and lab_name from Lab
         * insert and save the student
         */
        // REMEMBER TO VALIDATE THE REQUEST BODY
        $lab = Lab::where('id', $id)->get()->first();
        $lab_student = new LabStudent();
        $lab_student->lab_id = $lab->id;
        $lab_student->lab_name = $lab->lab_name;
        $lab_student->user_id = $request->user()->id;
        $lab_student->student_name = $request->user()->name;
        $lab_student->save();

        return response()->json([
            'status_code' => 201,
            'message' => 'student has been registered for lab'
        ]);


    }
}
