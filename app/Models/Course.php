<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'credit_hour',
        'Regulation',
        'Pre_Req'
    ];
    protected $primaryKey = 'id';
    public $incrementing = false;
}
