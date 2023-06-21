<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname','email', 'matricula' ,'password', 'tipo', 'tipo_str', 'status', 'api_token','CURP','calle','num_ext','num_int','entre_calles','referencias','colonia','municipio','estado','CP','estado_nacimiento','tel_celular','tel_casa','tipo_sangre','fecha_nacimiento','years_old','sexo','estado_civil','carrera','carrera_n','turno_carrera','promedio_bachiller','escuela_bachiller','lugar_bachiller','ingreso_bachiller','egreso_bachiller','discapacidad_desc','situacion_actual','tipo_descuento','creditos_cubiertos_n','porcentaje_cubierto_total_n','fecha_baja','beca_desc','fecha_solicitud','nombre_padre','fecha_nacimiento_padre','ocupacion_padre','ingresos_padre','tel_padre','email_padre','calle_padre','num_ext_padre','num_int_padre','entre_calles_padre','referencias_padre','colonia_padre','municipio_padre','estado_padre','CP_padre','nombre_madre','fecha_nacimiento_madre','ocupacion_madre','ingresos_madre','tel_madre','email_madre','calle_madre','num_ext_madre','num_int_madre','entre_calles_madre','referencias_madre','colonia_madre','municipio_madre','estado_madre','CP_madre'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }
}
