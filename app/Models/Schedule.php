<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'day',
        'period_from',
        'period_to',
        'place',
        'isSection',
        'isLab',
        'isLecture',
        'course_id',
        'section_id'
    ];

    public function course() {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }
    public function section() {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }
}
