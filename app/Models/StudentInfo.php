<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    use HasFactory;
    protected $table = 'teacher_infos';
    protected $fillable = [
        'user_id',
        'department',
        'roll_no',
    ];
}
