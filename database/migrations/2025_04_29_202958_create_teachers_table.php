<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Имя преподавателя
            $table->string('photo')->nullable(); // Фото преподавателя (опционально)
            $table->text('bio')->nullable(); // Биография преподавателя
            $table->timestamps();
        });

        // Промежуточная таблица для связи многие-ко-многим
        Schema::create('event_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_teacher');
        Schema::dropIfExists('teachers');
    }
}
