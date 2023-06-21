<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MateriasTmp as MateriasTMP;

class UtilController extends Controller
{
    public function getMaterias(Request $request)
    {

        $materias = MateriasTMP::select("id_materia", "nombre", "carrera")->get();
        $response = array(
            'menssage'      => 'success',
            'action'        => 'getMaterias',
            'materias'      => $materias,
            'codigo'        => 200
        );
        return response()->json($response, 200);
        
    }
}
