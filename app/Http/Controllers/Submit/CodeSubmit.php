<?php

namespace App\Http\Controllers\Submit;

use App\Http\Controllers\Controller;
use App\Models\CodeSubmission;
use Illuminate\Http\Request;

class CodeSubmit extends Controller
{
    // method to handle code submission
    public function submit(Request $request, $id) {
        if ($request->file()) {
            $userId = $request->user()->id;
            $submittedCodeName = time().'_';//.$request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('submissions', $submittedCodeName, 'public');
            
            $submission = new CodeSubmission();
            $submission->problem_id = $id;
            $submission->user_id = $userId;
            $submission->code_path = '/storage/'.$filePath;
            $submission->passed = false;
            $submission->save();
            
            return response()->json([
                'satus_code' => 201,
                'message' => 'Code Submitted'
            ]);

        }
    }
}
