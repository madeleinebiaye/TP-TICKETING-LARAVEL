<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

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
        'validation_comment',
        'validated_at',
        'project_id',
    ];

    protected $casts = [
        'collaborators' => 'array',
        'validated_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function getRemainingHoursAttribute(): int
    {
        return max(0, $this->hours_estimated - $this->hours_spent);
    }

    public function getBillableHoursAttribute(): int
    {
        return max(0, $this->hours_spent - $this->hours_estimated);
    }

    public function refreshHoursSpentFromTimeEntries(): void
    {
        $minutes = (int) $this->timeEntries()->sum('duration_minutes');

        // On garde le champ historique en heures pour ne pas casser l'existant.
        $this->hours_spent = (int) ceil($minutes / 60);
        $this->save();
    }

    public function getCollaboratorLabelsAttribute(): array
    {
        $collaborators = $this->collaborators ?? [];

        if ($collaborators === []) {
            return [];
        }

        $collaboratorIds = collect($collaborators)
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->values();

        $labelsById = $collaboratorIds->isEmpty()
            ? collect()
            : User::query()
                ->whereIn('id', $collaboratorIds->all())
                ->orderBy('name')
                ->get(['id', 'name', 'email'])
                ->mapWithKeys(fn (User $user) => [$user->id => $user->name.' ('.$user->email.')']);

        return collect($collaborators)
            ->map(function ($value) use ($labelsById) {
                if (is_numeric($value) && $labelsById->has((int) $value)) {
                    return $labelsById[(int) $value];
                }

                return (string) $value;
            })
            ->filter()
            ->values()
            ->all();
    }
}
