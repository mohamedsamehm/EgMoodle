<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'gpa',
        'hours',
        'level',
        'department',
        'Regulation',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function enrollments()
    {
        return $this->hasMany('App\Models\Enrollment', 'student_id', 'id');
    }
}
