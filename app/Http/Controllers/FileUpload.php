<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileUpload extends Controller
{
    //
    public function fileUpload(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048'
        ]);
        if ($request->file()) {
            $fileModel = new File();
            $fileName = time().'_'.$request->file->getClientOriginalName();
            //$filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $filePath = $request->file('file')->storeAs('codes', $fileName, 'public');
            
            $fileModel->name = $fileName;
            $fileModel->file_path = '/storage/'.$filePath;
            $fileModel->save();

            return response()->json([
                'status_code'=>201,
                'message'=>'Upload succes'
            ]);
        }
    }
}
