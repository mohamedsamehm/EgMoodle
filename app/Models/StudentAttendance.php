<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'meeting_id',
        'student_id',
    ];
    public function assignment() {
        return $this->belongsTo('App\Models\Meeting', 'meeting_id');
    }
    public function student() {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }
}
