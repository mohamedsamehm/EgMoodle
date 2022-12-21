<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswers extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_exam_id',
        'answer_id',
    ];
    public function student() {
        return $this->belongsTo('App\Models\StudentExam', 'student_exam_id');
    }
    public function answer()
    {
        return $this->belongsTo('App\Models\Option', 'answer_id');
    }
}
