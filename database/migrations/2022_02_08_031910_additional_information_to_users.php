<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalInformationToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('CURP',              20)->nullable();
            $table->string('calle',             50)->nullable();
            $table->string('num_ext',           20)->nullable();
            $table->string('num_int',           20)->nullable();
            $table->string('entre_calles',      50)->nullable();
            $table->string('referencias',       50)->nullable();
            $table->string('colonia',           50)->nullable();
            $table->string('municipio',         50)->nullable();
            $table->string('estado',            50)->nullable();
            $table->string('CP',                5)->nullable();
            $table->string('estado_nacimiento', 50)->nullable();
            $table->string('tel_celular',       10)->nullable();
            $table->string('tel_casa',          10)->nullable();
            $table->string('tipo_sangre',       20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('years_old')->nullable();
            $table->string('sexo',              10)->nullable();
            $table->string('estado_civil',      25)->nullable();
            $table->string('carrera',           80)->nullable();
            $table->integer('carrera_n')->nullable();
            $table->string('turno_carrera',     15)->nullable();
            $table->string('promedio_bachiller',5)->nullable();
            $table->string('escuela_bachiller', 80)->nullable();
            $table->string('lugar_bachiller',   250)->nullable();
            $table->integer('ingreso_bachiller')->nullable();
            $table->integer('egreso_bachiller')->nullable();
            $table->string('discapacidad_desc', 250)->nullable();
            $table->string('situacion_actual',  250)->nullable();
            $table->string('tipo_descuento',    20)->nullable();
            $table->integer('creditos_cubiertos_n')->nullable();
            $table->integer('porcentaje_cubierto_total_n')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->string('beca_desc',         50)->nullable();
            $table->date('fecha_solicitud')->nullable();

            $table->string('nombre_padre',          100)->nullable();
            $table->date('fecha_nacimiento_padre'   )->nullable();
            $table->string('ocupacion_padre',       50)->nullable();
            $table->string('ingresos_padre',        50)->nullable();
            $table->string('tel_padre',             10)->nullable();
            $table->string('email_padre',           100)->nullable();
            $table->string('calle_padre',          50)->nullable();
            $table->string('num_ext_padre',        20)->nullable();
            $table->string('num_int_padre',         20)->nullable();
            $table->string('entre_calles_padre',    50)->nullable();
            $table->string('referencias_padre',     50)->nullable();
            $table->string('colonia_padre',         50)->nullable();
            $table->string('municipio_padre',       50)->nullable();
            $table->string('estado_padre',          50)->nullable();
            $table->string('CP_padre',              5)->nullable();

            $table->string('nombre_madre',          100)->nullable();
            $table->date('fecha_nacimiento_madre'   )->nullable();
            $table->string('ocupacion_madre',       50)->nullable();
            $table->string('ingresos_madre',        50)->nullable();
            $table->string('tel_madre',             10)->nullable();
            $table->string('email_madre',           100)->nullable();
            $table->string('calle_madre',           50)->nullable();
            $table->string('num_ext_madre',         20)->nullable();
            $table->string('num_int_madre',         20)->nullable();
            $table->string('entre_calles_madre',    50)->nullable();
            $table->string('referencias_madre',     50)->nullable();
            $table->string('colonia_madre',         50)->nullable();
            $table->string('municipio_madre',       50)->nullable();
            $table->string('estado_madre',          50)->nullable();
            $table->string('CP_madre',              5)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['CURP']);
            $table->dropColumn(['calle']);
            $table->dropColumn(['num_ext']);
            $table->dropColumn(['num_int']);
            $table->dropColumn(['entre_calles']);
            $table->dropColumn(['referencias']);
            $table->dropColumn(['colonia']);
            $table->dropColumn(['municipio']);
            $table->dropColumn(['estado']);
            $table->dropColumn(['CP']);
            $table->dropColumn(['estado_nacimiento']);
            $table->dropColumn(['tel_celular']);
            $table->dropColumn(['tel_casa']);
            $table->dropColumn(['tipo_sangre']);
            $table->dropColumn(['fecha_nacimiento']);
            $table->dropColumn(['years_old']);
            $table->dropColumn(['sexo']);
            $table->dropColumn(['estado_civil']);
            $table->dropColumn(['carrera']);
            $table->dropColumn(['carrera_n']);
            $table->dropColumn(['turno_carrera']);
            $table->dropColumn(['promedio_bachiller']);
            $table->dropColumn(['escuela_bachiller']);
            $table->dropColumn(['lugar_bachiller']);
            $table->dropColumn(['ingreso_bachiller']);
            $table->dropColumn(['egreso_bachiller']);
            $table->dropColumn(['discapacidad_desc']);
            $table->dropColumn(['situacion_actual']);
            $table->dropColumn(['tipo_descuento']);
            $table->dropColumn(['creditos_cubiertos_n']);
            $table->dropColumn(['porcentaje_cubierto_total_n']);
            $table->dropColumn(['fecha_baja']);
            $table->dropColumn(['beca_desc']);
            $table->dropColumn(['fecha_solicitud']);

            $table->dropColumn(['nombre_padre']);
            $table->dropColumn(['fecha_nacimiento_padre']);
            $table->dropColumn(['ocupacion_padre']);
            $table->dropColumn(['ingresos_padre']);
            $table->dropColumn(['tel_padre']);
            $table->dropColumn(['email_padre']);
            $table->dropColumn(['calle_padre']);
            $table->dropColumn(['num_ext_padre']);
            $table->dropColumn(['num_int_padre']);
            $table->dropColumn(['entre_calles_padre']);
            $table->dropColumn(['referencias_padre']);
            $table->dropColumn(['colonia_padre']);
            $table->dropColumn(['municipio_padre']);
            $table->dropColumn(['estado_padre']);
            $table->dropColumn(['CP_padre']);


            $table->dropColumn(['nombre_madre']);
            $table->dropColumn(['fecha_nacimiento_madre']);
            $table->dropColumn(['ocupacion_madre']);
            $table->dropColumn(['ingresos_madre']);
            $table->dropColumn(['tel_madre']);
            $table->dropColumn(['email_madre']);
            $table->dropColumn(['calle_madre']);
            $table->dropColumn(['num_ext_madre']);
            $table->dropColumn(['num_int_madre']);
            $table->dropColumn(['entre_calles_madre']);
            $table->dropColumn(['referencias_madre']);
            $table->dropColumn(['colonia_madre']);
            $table->dropColumn(['municipio_madre']);
            $table->dropColumn(['estado_madre']);
            $table->dropColumn(['CP_madre']);

        });
    }
}
