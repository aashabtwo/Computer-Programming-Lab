<?php

namespace App\Http\Controllers\Password;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mockery\Generator\StringManipulation\Pass\Pass;

class ForgotPasswordController extends Controller
{
    // method to send a password reset link
    public function sendLink(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            // return a fail message
            return response()->json([
                'message' => 'Bad Request',
            ], 400);
        }
        // else
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status == Password::RESET_LINK_SENT
                    ? back()->with(['stastus' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
        }
        catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'class' => get_class($exception)
            ]);
        }

    }
    // method to reset password
    public function reset(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
                'token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Bad Request',
                ], 400);
            }
            
            
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->setRememberToken(Str::random(60));
        
                    $user->save();
        
                    event(new PasswordReset($user));
                }
            );
        
            return $status == Password::PASSWORD_RESET
                        ? redirect()->with('status', __($status))
                        : back()->withErrors(['email' => [__($status)]]);
            
            
        }
        catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'class' => get_class($exception)
            ]);
        }
    }
}
