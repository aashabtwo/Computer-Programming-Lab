<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckIfTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        if ($request->user()->position == 'Teacher') {
            return $next($request);
        }
        else {
            return response()->json([
                "status_code" => 403,
                "Message" => "You are not allowed to access this page"
            ]);
        }
    }
}
