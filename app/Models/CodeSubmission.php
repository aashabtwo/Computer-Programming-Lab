<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeSubmission extends Model
{
    use HasFactory;
    protected $table = 'code_submissions';
    protected $fillable = [
        'problem_id',
        'user_id',
        'code_path',
        'passed',
    ];
}
