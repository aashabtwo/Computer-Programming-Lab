<?php

namespace App\Http\Middleware;

use App\Models\LabTeacher;
use Closure;
use Illuminate\Http\Request;

class IsLab
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
        $lab_id = $request->route('id');
        $teacher_id = $request->user()->id;
        $lab = LabTeacher::where('user_id', $teacher_id)->where('lab_id', $lab_id)->get()->first();
        if ($lab) {
            return $next($request);
        }
        else {
            return response()->json([
                "message" => "Forbidden"
            ], 403);
        }
        //return $next($request);
    }
}
