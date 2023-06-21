<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MateriasTmp extends Model
{
    protected $table = 'materias';
    protected $fillable = ['id_materia', 'nombre', 'carrera', 'semestre'];
    protected $guarded = ['id'];
}
