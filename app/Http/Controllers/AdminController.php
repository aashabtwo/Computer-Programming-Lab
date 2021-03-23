<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;

class AdminController extends Controller
{
    // method to delete a user
    public function deleteUser(Request $request) {
        /**
         * 
         * revoke all of their tokens
        */
        $user = User::find($request->route('id'));
        $user->delete();
        return response()->json([
            'message' => 'User deleted!',
        ]);
    }
}
