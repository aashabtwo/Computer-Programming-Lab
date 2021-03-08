<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Student
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
        // middleware to only allow student
        if ($request->user()->position == 'Student') {
            return $next($request);
        }
        else {
            return response()->json([
                "Message" => "Page not found"
            ], 404);
        }
    }
}
