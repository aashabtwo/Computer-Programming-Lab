<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
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
        if ($request->user()->position == 'admin') {
            return $next($request);
        }
        else {
            return response()->json([
                "Message" => "You are not allowed to access this page",
            ], 401);
        }
    }
}
