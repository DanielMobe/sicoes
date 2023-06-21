<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materias extends Model
{
    protected $table = 'materias';
    protected $fillable = ['id_materia', 'nombre'];
    protected $guarded = ['id'];
    public $timestamps = false;
}
