<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabStudent;
use Illuminate\Http\Request;

class StudentLabController extends Controller
{
    // method to display the labs the student is registered in
    public function labs(Request $request) {
        /**
         * show only the labs the student registered in
         * pull the user id to query LabStudent model where
         * this student is registered in
         */
        $user_id = $request->user()->id;
        $labs = LabStudent::where('user_id', $user_id)->get();
        if ($labs) {
            return response()->json([
                'labs' => $labs,
                'user_id' => $user_id
            ]);
        }
        else {
            return response()->json([
                'message' => 'You have not joined any labs yet'
            ], 403);
        }
    }

    // method to get one lab
    public function lab(Request $request, $id) {
        $lab = LabStudent::where('id', $id)->where('user_id',)->get()->toJson(JSON_PRETTY_PRINT);
        if ($lab) {
            return response($lab, 200);
        }
        else {
            return response()->json([
                'message' => 'Lab does not exist'
            ], 404);
        }
    }

    // method to check all given assignments
    // method to check one assignment
    // method to submit an assignment
    
}
