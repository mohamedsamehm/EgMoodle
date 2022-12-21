<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'option',
        'answer',
        'question_id',
    ];
    public function material() {
        return $this->belongsTo('App\Models\Question', 'question_id');
    }
}
