<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_reports', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Human weather description (eg. Clear Skyes)
            $table->decimal('temp', 5, 2); // Degrees C
            $table->unsignedInteger('wind_dir'); // Wind Direction (Heading)
            $table->decimal('wind_spd', 5, 2); // Wind Speed (MPS)
            $table->unsignedInteger('pressure'); // Pressure (hPa)
            $table->unsignedInteger('humidity'); // Humidity (%)
            $table->decimal('reported_lat', 8, 5); // The WX station lat
            $table->decimal('reported_lon', 8, 5); // The WX station lon
            $table->unsignedInteger('reported_at'); // The UNIX timestamp when weather report added to OWM.
            $table->unsignedInteger('created_at'); // The UNIX timestamp when the record was added to the database.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weather_reports');
    }
}
