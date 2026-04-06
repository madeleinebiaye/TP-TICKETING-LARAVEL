<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'type',
        'priority',
        'collaborators',
        'hours_estimated',
        'hours_spent',
        'project_id',
    ];

    protected $casts = [
        'collaborators' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getRemainingHoursAttribute(): int
    {
        return max(0, $this->hours_estimated - $this->hours_spent);
    }

    public function getBillableHoursAttribute(): int
    {
        return max(0, $this->hours_spent - $this->hours_estimated);
    }
}
