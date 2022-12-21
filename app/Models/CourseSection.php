<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'section_id',
    ];
    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
    public function section() {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }
}
