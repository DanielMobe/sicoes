<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalificacionesTmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificaciones_tmps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_maestro');
            $table->integer('grupoId');
            $table->integer('materiaId');
            $table->string('alumnoMatricula');
            $table->string('calificacionInt');
            $table->integer('tipoCal');
            $table->string('periodo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificaciones_tmps');
    }
}
