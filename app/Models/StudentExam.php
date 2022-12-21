<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_id',
        'student_id',
        'grade'
    ];
    public function exam() {
        return $this->belongsTo('App\Models\Exam', 'exam_id');
    }
    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
}
