<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalificacionesBitacora as CalificacionesBit;


class BitacoraController extends Controller
{
    public function getCalificacionesBitacora(Request $request){

        $bitacora = CalificacionesBit::select();

        if ((isset($request['accion'])) && (!empty($request['accion'])) && ($request['accion']!='')) 
        {   $bitacora = $bitacora->where('accion','=',$request['accion']);  }

        if ((isset($request['user_type'])) && (!empty($request['user_type'])) && ($request['user_type']!='')) 
        {   $bitacora = $bitacora->where('user_type','=',$request['user_type']);    }

        if ((isset($request['alumno_matricula'])) && (!empty($request['alumno_matricula'])) && ($request['alumno_matricula']!='')) 
        {   $bitacora = $bitacora->where('alumno_matricula','=',$request['alumno_matricula']);  }

        /* if ((isset($request[''])) && (!empty($request[''])) && ($request['']!='')) {} */

        $bitacora = $bitacora->get();

        if ((!empty($bitacora)) && (count($bitacora)>0)) {
            $response = array(
                'bitacora'      => $bitacora,
                'menssage'      => 'success',
                'action'        => 'getCalificacionesBitacora',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        } else {
            $response = array(
                'menssage'      => 'No se encontraron registros',
                'action'        => 'getCalificacionesBitacora',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
}
