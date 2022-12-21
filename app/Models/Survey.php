<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    protected $fillable = [
        'questions',
        'student_id',
        'course_id',
    ];
    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
}
