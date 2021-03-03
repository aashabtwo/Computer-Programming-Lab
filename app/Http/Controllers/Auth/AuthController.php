<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request) {
        /**
         * validate and sanitize request body
         * make sure the password has a minimum length of 8 characters
         * position should have a maximum of 12 characters and name
         * should have a maximum of 250 characters
         * name, email, password and position should be required
         * If validation fails, send a status code of 400 with a message
         * Else, create a new user and send a status code of 200 with a success message
         * NOTE: make sure the field 'position' is added to the fillable
         */
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|email',
            'password' => 'required',
            'position' => 'required|max:12',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Bad Request'
            ]);
        }
        else {
            $user = new User();
            $user->name = $request->name;
            $user->position = $request->position;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            
            return response()->json([
                'status_code' => 201,
                'message' => 'User created successfully'
            ]);
        }

    }
}
