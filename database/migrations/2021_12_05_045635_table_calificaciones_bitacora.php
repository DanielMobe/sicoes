<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableCalificacionesBitacora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calificaciones_bitacora', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_calificacion');
            $table->string('alumno_matricula', 30);
            $table->integer('id_user');
            $table->string('user_type');
            $table->string('accion');
            $table->json('calificacion_before')->nullable(); 
            $table->json('calificacion_after')->nullable(); 
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
        Schema::dropIfExists('calificaciones_bitacora');
    }
}
