<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'practical_parts', 'methodologies',
        'photo', 'start_date', 'end_date', 'total_seats', 'occupied_seats',
    ];

    protected $casts = [
        'practical_parts' => 'array',
        'methodologies' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Добавляем вычисляемое свойство
    public function getAvailableSeatsAttribute(): int
    {
        return $this->total_seats - $this->occupied_seats;
    }

    // Связь с регистрациями
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
