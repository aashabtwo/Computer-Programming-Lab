<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // find a way to log errors
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
        try {
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
                // try -> registering
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
                // catch -> if any error occues, and log the error in the log file
                // $exception -> getMessage() (exception message)
                // get_class($exception) (exception class)
            }
        }
        catch (\Exception $exception) {
            $exception_class = get_class($exception);
            $exception_message = $exception->getMessage();
            // log the exception class and message in registration errors channel
            Log::channel('regerrors')->error([
                'message' => $exception_message,
                'class' => $exception_class
            ]);
            // return a response to user
            return response()->json([
                'message' => $exception_message
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad Request, try again'
            ], 400);
        }

        try {
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
                    Log::channel('loginmessages')->alert([
                        'Attempted Login with wrong PASSWORD'
                    ]);
                    return response()->json([
                        'status_code'=>400,
                        'message'=>"Your password does not match"
                    ]);
                }
            }
            else {
                Log::channel('loginmessages')->alert([
                    'Attempted Login with wrong EMAIL'
                ]);
                return response()->json([
                    'status_code'=>422,
                    'message'=>"No user with this email found"
                ]);
            }
        }   
        catch(\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
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
