<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabAssignment extends Model
{
    use HasFactory;
    protected $table = 'lab_assignments';
    protected $fillable = [
        'title',
        'description',
        'objective',
        'task',
        'input_content',
        'output_content',
        'iter_num',
        'marks',
        'lab_name',
        'lab_id',
        'assigned_by',
    ];
}
