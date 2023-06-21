<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalificacionesBitacora extends Model
{

    protected $table = 'calificaciones_bitacora';

    protected $fillable = [
        'id_calificacion', 
        'alumno_matricula',
        'id_user', 
        'user_type', 
        'accion', 
        'calificacion_before',
        'calificacion_after',
    ];

    protected $casts = [
        'calificacion_before' => 'array',
        'calificacion_after' => 'array',
    ];
}
