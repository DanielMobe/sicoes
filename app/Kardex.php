<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table = 'kardex';
    protected $fillable = ['materia', 'materia'];
    protected $guarded = ['matricula'];
}
