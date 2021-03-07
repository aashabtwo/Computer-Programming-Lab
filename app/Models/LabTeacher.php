<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTeacher extends Model
{
    use HasFactory;
    protected $table = 'lab_teachers';
    protected $fillable = [
        'lab_id',
        'lab_name',
        'user_id', // for teacher position only
        'teacher_name',
    ];
}
