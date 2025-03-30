<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Массив заполняемых полей
    protected $fillable = [
        'title',              // Название мероприятия
        'description',        // Описание
        'practical_parts',    // Практические части (массив)
        'methodologies',      // Методологии (массив)
        'photo',             // Путь к фото
        'start_date',        // Дата начала
        'end_date',          // Дата окончания
        'total_seats',       // Общее количество мест
        'occupied_seats',    // Занятые места
    ];

    // Приведение типов
    protected $casts = [
        'practical_parts' => 'array',  // Автоматическое преобразование JSON в массив
        'methodologies' => 'array',    // Автоматическое преобразование JSON в массив
        'start_date' => 'date',        // Преобразование в объект Carbon
        'end_date' => 'date',          // Преобразование в объект Carbon
    ];

    // Вычисляемое свойство для доступных мест
    public function getAvailableSeatsAttribute(): int
    {
        return $this->total_seats - $this->occupied_seats;
    }

    // Связь один-ко-многим с регистрациями
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
