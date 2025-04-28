<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutorPendingAction extends Model
{
    use HasFactory;

    public const FIO_PENDING_ACTION_ID = 3;
    public const AGE_PENDING_ACTION_ID = 4;
    public const NUMBER_PENDING_ACTION_ID = 5;

    protected $fillable = [
        'executor_id', 'pending_action_id'
    ];

    protected $table = 'executor_pending_actions';
}
