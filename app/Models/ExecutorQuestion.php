<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutorQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'executor_id', 'question_id', 'value'
    ];

    protected $table = 'executor_questions';
}
