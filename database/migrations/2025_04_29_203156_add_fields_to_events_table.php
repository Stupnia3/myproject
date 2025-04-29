<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('occupied_seats'); // Теги (арт-терапия, мастер-класс, ретрит)
            $table->string('location')->nullable()->after('tags'); // Место проведения
            $table->integer('duration')->nullable()->after('location'); // Длительность в часах
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['tags', 'location', 'duration']);
        });
    }
}
