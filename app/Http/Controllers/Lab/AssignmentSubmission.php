<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\AssignmentSubmission as ModelsAssignmentSubmission;
use Illuminate\Http\Request;

class AssignmentSubmission extends Controller
{
    // method to handle assingment submission
    public function submit(Request $request) {
        if ($request->file()) {
            $submissionName = time().'_'.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('assignment_submissions', $submissionName, 'public');
            
            $user_id = $request->user()->id;
            $lab_id = $request->route('id');
            $assignment_id = $request->route('assignment_id');
            $assignment_submission = new ModelsAssignmentSubmission();
            $assignment_submission->lab_assignment_id = $assignment_id;
            $assignment_submission->lab_id = $lab_id;
            $assignment_submission->user_id = $user_id;
            $assignment_submission->code_path = '/storage/'. $filePath;
            $assignment_submission->passed = false;
            $assignment_submission->reviewed = false;
            $assignment_submission->approved = false;
            $assignment_submission->remarks = 'none';

            $assignment_submission->save();
            return response()->json([
                'message' => 'submitted',
                'instance' => $assignment_submission
            ], 200);
            
        }
    }
}
