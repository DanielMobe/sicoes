<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carreras extends Model
{
    protected $table = 'carreras';
    protected $fillable = ['id_carrera', 'carrera'];
    public $timestamps = false;
}
