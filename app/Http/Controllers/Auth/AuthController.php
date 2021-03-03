<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $user->password = Hash::make($request->password);
            
            $user->save();
            $token = $user->createToken('Password Grant client')->accessToken;
            $response = ['token'=>$token];
            
            return response()->json([
                'status_code' => 201,
                'message' => 'User created successfully',
                'token'=>$token
            ]);
        }

    }
    public function login(Request $request) {
        /**
         * Validate and sanitize the request body
         * Check if the user with this given email exists
         * Send a "No User Found" message if the user with this email does not exist
         * Otherwise, use Hash facade to check if the passwords match
         * Send an appropriate message if they don't
         * Otherwise, get the access token (accessToken()) from 
         * the Password Grant client object (createToken('Password Grant client'))
         * Send a success message and the token
         */
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Password Grant client')->accessToken;
                $response = ['token'=>$token];
                return response()->json([
                    'status_code'=>200,
                    'message'=>'Logged in',
                    'token'=>$token
                ]);
            }
            else {
                return response()->json([
                    'status_code'=>400,
                    'message'=>"password does not match"
                ]);
            }
        }
        else {
            return response()->json([
                'status_code'=>422,
                'message'=>"No user found"
            ]);
        }
    }
    public function logout(Request $request) {
        /**
         * Get the the token from the user
         * Revoke the token and send an appropriate message
         */
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'status_code'=>200,
            'message'=>"Logged out"
        ]);
    }
}
