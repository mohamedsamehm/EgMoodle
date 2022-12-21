<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'exam_id',
        'type'
    ];
    public function exam() {
        return $this->belongsTo('App\Models\Exam', 'exam_id');
    }
}
