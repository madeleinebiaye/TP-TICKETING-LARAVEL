<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
    'title',
    'description',
    'hours_estimated',
    'hours_spent'
];

public function getRemainingHoursAttribute()
{
    return max(0, $this->hours_estimated - $this->hours_spent);
}

public function getBillableHoursAttribute()
{
    return max(0, $this->hours_spent - $this->hours_estimated);
}
}
