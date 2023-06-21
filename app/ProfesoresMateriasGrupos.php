<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfesoresMateriasGrupos extends Model
{
    protected $table = 'profesores';
    protected $fillable = ['nombre_profesor', 'clave_materia', 'grupo', 'carrera'];
    protected $guarded = ['id_profesor'];
}
