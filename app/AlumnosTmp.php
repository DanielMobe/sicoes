<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumnosTmp extends Model
{
    protected $table = 'alumnos';
    protected $fillable = ['carrera', 'matricula', 'semestre', 'grupo'];
    protected $guarded = ['id'];
}
