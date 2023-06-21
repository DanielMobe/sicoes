<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Periodos as Periodo;

//use Barryvdh\DomPDF\PDF;


class periodosController extends Controller
{
	
    public function consultaPeriodo(Request $request){
        $Periodos = Periodo::all();
        if (!empty($Periodos)) {
            $response = array(
                'menssage'          => 'success',
                'action'            => 'getAllPeriodos Admin',
                'periodos'           => $Periodos,
                'codigo'            => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'No se encontraron registros.',
                'action'        => 'getAllPeriodos Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function show(Periodos $periodo)
    {
        return $periodo;
    }

    public function store(Request $request)
    {
        if((isset($request['year'])) && (!empty($request['periodo']))){
            $ExistPeriodo = 'TESI'.$request['year'].$request['periodo'];

            if (Periodo::where('id_periodo', '=',$ExistPeriodo)->exists()) {

                $response = array(
                    'menssage' => 'Existe',
                    'action' => 'El periodo asignado ya existe, digite otro',
                    'codigo' => 400
                );
                return response()->json($response, 400);

            }else{
                $year_evaluacion = substr($request['year'], -2);
                $periodo_evaluacion = $year_evaluacion.'-'.$request['periodo'];
                DB::table('periodos_escolares')->insert([
                    'id_periodo' => $ExistPeriodo,
                    'year' => $request['year'],
                    'periodo' => $request['periodo'],
                    'estado' => 0,
                    'periodo_evaluacion' => $periodo_evaluacion
                ]);
                $response = array(
                    'menssage' => 'success',
                    'action' => 'Se creo el nuevo periodo',
                    'codigo' => 201
                );
                return response()->json($response, 201);

            }
        }else{
			return response()->json($response = array('menssage' => 'Los campos Periodo y Año son necesarios'), 400);
		}
    }

        public function ActivarPeriodo(Request $request)
        {
            if((isset($request['year'])) && (!empty($request['periodo']))){
                $ExistPeriodo = 'TESI'.$request['year'].$request['periodo'];
                $periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();
                if (!empty($periodo_activo)) {
                    if($periodo_activo['id_periodo'] <> $ExistPeriodo){
                        $response = array(
                            'menssage' => 'Existe_Activo',
                            'action' => 'Ya existe un periodo activo',
                            'codigo' => 400
                        );
                        return response()->json($response, 400);

                    }else{

                        $update = Periodo::where('id_periodo','=',$ExistPeriodo)->update(array('estado' => 1));
                        //$update = Periodo::find($ExistPeriodo);
                        //$update->estado = 1;
                        //$update->save();
                        $response = array(
                            'menssage' => 'Periodo activado',
                            'action' => 'El periodo se activo',
                            'codigo' => 201
                        );
                        return response()->json($response, 201);
                    }
                }else{
                    $update = Periodo::where('id_periodo','=',$ExistPeriodo)->update(array('estado' => 1));
                        //$update = Periodo::find($ExistPeriodo);
                        //$update->estado = 1;
                        //$update->save();
                        $response = array(
                            'menssage' => 'Periodo activado',
                            'action' => 'El periodo se activo',
                            'codigo' => 201
                        );
                        return response()->json($response, 201);
                }
            }else{
                return response()->json($response = array('menssage' => 'Los campos Periodo y Año son necesarios'), 400);
            }
    }

    public function delete(Request $request)
    {
        if(isset($request['id_periodo'])){
            if (Periodo::where('id_periodo', '=',$request['id_periodo'])->exists()) {
                if (Periodo::where('id_periodo', '=',$request['id_periodo'])->where('estado','<>',1)->exists()) {
                    $result=Periodo::where('id_periodo','=',$request['id_periodo'])->delete();

                    $response = array(
                        'menssage' => 'PeriodoEliminado',
                        'action' => 'Se ha eliminado el periodo',
                        'codigo' => 201
                    );
                    return response()->json($response, 201);
                }else{
                    $response = array(
                        'menssage' => 'PeriodoActivo',
                        'action' => 'El periodo se encuentra Activo, no puede ser elimniado',
                        'codigo' => 400
                    );
                    return response()->json($response, 400);
                }
            }else{
                $response = array(
                    'menssage' => 'PeriodoNoExiste',
                    'action' => 'El periodo No existe',
                    'codigo' => 400
                );
                return response()->json($response, 400);
            }
        }else{
            return response()->json($response = array('menssage' => 'El campo id_periodo es necesario'), 400);
        }
    }
}//cierre de clase principal