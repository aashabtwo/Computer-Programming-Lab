<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;
    protected $table = 'labs';
    protected $fillable = [
        'lab_name',
        'lab_category',
        'department',
    ];
}
