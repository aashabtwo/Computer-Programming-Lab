<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\StudentInfo;
use App\Models\TeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InfoController extends Controller
{
    // method to add user info
    public function addInfoTeacher(Request $request) {
        $validator = Validator::make($request->all(), [
            'department' => 'required|max:50',
            'department_position' => 'required|max:12' // professor, assistant professor, lecturer, etc
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request'
            ], 400);
        }
        // else, add the information
        $teacher_info = new TeacherInfo();
        $teacher_info->department = $request->department;
        $teacher_info->department_position = $request->department_position;
        $teacher_info->user_id = $request->user()->id;

        $teacher_info->save();

        return response()->json([
            'message' => 'Success'
        ], 201);

    }

    // method to add student
    public function addInfoStudent(Request $request) {
        $validator = Validator::make($request->all(), [
            'department' => 'required|max:50',
            'roll_no' => 'required|max:12' // professor, assistant professor, lecturer, etc
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request'
            ], 400);
        }
        // else, add the information
        $student_info = new StudentInfo();
        $student_info->department = $request->department;
        $student_info->roll_no = $request->roll_no;
        $student_info->user_id = $request->user()->id;

        $student_info->save();

        return response()->json([
            'message' => 'Success'
        ], 201);

    }
}
