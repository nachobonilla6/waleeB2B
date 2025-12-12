<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkflowRun extends Model
{
    protected $fillable = [
        'job_id',
        'status',
        'step',
        'progress',
        'result',
        'data',
        'workflow_name',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'result' => 'array',
        'data' => 'array',
        'progress' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'running' => 'primary',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'gray',
        };
    }
}
