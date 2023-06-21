<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalificacionesTmp extends Model
{
    protected $table = 'calificaciones_tmps';
    protected $fillable = ['id_maestro', 'grupoId', 'materiaId', 'alumnoMatricula', 'calificacionInt', 'tipoCal', 'periodo'];
    protected $guarded = ['id'];
}
