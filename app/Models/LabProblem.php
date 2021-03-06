<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabProblem extends Model
{
    use HasFactory;
    protected $table = 'lab_problems';
    protected $fillable = [
        //'head',
        'title',
        'description',
        'objective',
        'task',
        'input_content',
        'output_content',
        'iter_num',
        // number of test cases
    ];
}
