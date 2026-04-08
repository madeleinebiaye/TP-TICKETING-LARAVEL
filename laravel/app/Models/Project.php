<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'client_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function consumedIncludedMinutes(): int
    {
        return (int) $this->tickets()
            ->where('type', 'Inclus')
            ->withSum('timeEntries', 'duration_minutes')
            ->get()
            ->sum('time_entries_sum_duration_minutes');
    }

    public function billableMinutes(): int
    {
        return (int) $this->tickets()
            ->where('type', 'Facturable')
            ->withSum('timeEntries', 'duration_minutes')
            ->get()
            ->sum('time_entries_sum_duration_minutes');
    }

    public function remainingIncludedMinutes(): int
    {
        $includedMinutes = (int) (($this->contract?->included_hours ?? 0) * 60);

        return max(0, $includedMinutes - $this->consumedIncludedMinutes());
    }
}