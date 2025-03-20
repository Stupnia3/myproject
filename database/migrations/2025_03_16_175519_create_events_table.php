<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Заголовок
            $table->text('description'); // Описание
            $table->json('practical_parts'); // Практическая часть (список)
            $table->json('methodologies'); // Методики (список)
            $table->string('photo')->nullable(); // Фото (путь к файлу)
            $table->date('start_date'); // Дата начала
            $table->date('end_date')->nullable(); // Дата окончания (может быть период)
            $table->integer('total_seats'); // Число мест
            $table->integer('occupied_seats')->default(0); // Заполненные места
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
