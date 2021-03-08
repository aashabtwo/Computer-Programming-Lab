<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabStudent extends Model
{
    use HasFactory;
    protected $table = 'lab_students';
    protected $fillable = [
        'lab_id',
        'lab_name',
        'user_id',
        'student_name',
    ];
}
