<?php

namespace App\Http\Middleware;

use App\Models\LabStudent;
use Closure;
use Illuminate\Http\Request;

class StudentLab
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
        $student_id = $request->user()->id;
        $lab = LabStudent::where('user_id', $student_id)->where('lab_id', $lab_id)->get()->first();
        if ($lab) {
            return $next($request);
        }
        else {
            return response()->json([
                "message" => "Forbidden"
            ], 403);
        }
    }
}
