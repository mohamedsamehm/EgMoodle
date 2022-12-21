<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignment extends Model
{
    use HasFactory;
    protected $fillable = [
        'path',
        'name',
        'assignment_id',
        'student_id',
        'grade',
    ];
    public function assignment() {
        return $this->belongsTo('App\Models\Assignment', 'assignment_id');
    }
    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
}
