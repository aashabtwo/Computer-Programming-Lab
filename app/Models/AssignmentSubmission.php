<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;
    protected $table = 'assignment_submissions';
    protected $fillable = [
        'lab_assignment_id', // foreign key - same principle as "problem_id"
        'lab_id',   // foreign key
        'user_id',  // foreign key
        'code_path',// path
        'passed',   // bool
        'reviewed', // bool
        'approved', // bool
        'remarks',  // teachers' remarks
        'submitted_by'
    ];
}
