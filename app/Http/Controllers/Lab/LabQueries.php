<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Lab;
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
}
