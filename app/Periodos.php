<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodos extends Model
{
    protected $table = 'periodos_escolares';
    protected $fillable = ['id_periodo'];
    protected $guarded = ['id_periodo'];

    public $timestamps = false;
}
