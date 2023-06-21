<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Alumnos as AlumnosRef;
use App\Maestros as Maestros;
use App\User as Alumnos;
use App\Materias as Materia;
use App\Carreras as Carrera;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    public function getMaestro(Request $request){
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) && ($request['id_maestro'] != '')){
            $maestro = Maestros::where('id','=',$request['id_maestro'])->first();
            if (!empty($maestro)) {
                $response = array(
                    'menssage'          => 'success',
                    'action'            => 'getMaestro Admin',
                    'maestro'           => $maestro,
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'getMaestro Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_maestro es requerido.',
                'action'        => 'getMaestro Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getAlumno(Request $request){
        if((isset($request['id_alumno'])) && (!empty($request['id_alumno'])) && ($request['id_alumno'] != '')){
            $alumno = Alumnos::where('id','=',$request['id_alumno'])->first();
            if (!empty($alumno)) {
                $response = array(
                    'menssage'          => 'success',
                    'action'            => 'getAlumno Admin',
                    'alumno'            => $alumno,
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'getAlumno Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_alumno es requerido.',
                'action'        => 'getAlumno Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getAllMaestros(Request $request){
        $maestros = Maestros::all();
        if (!empty($maestros)) {
            $response = array(
                'menssage'          => 'success',
                'action'            => 'getAllMaestros Admin',
                'maestros'           => $maestros,
                'codigo'            => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'No se encontraron registros.',
                'action'        => 'getAllMaestros Admin',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }
    }

    public function getAllAlumnos(Request $request){
        $alumnos = Alumnos::all();
        if (!empty($alumnos)) {
            $response = array(
                'menssage'          => 'success',
                'action'            => 'getAllAlumnos Admin',
                'alumnos'           => $alumnos,
                'codigo'            => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'No se encontraron registros.',
                'action'        => 'getAllAlumnos Admin',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }
    }

    public function updateMaestro(Request $request){
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) && ($request['id_maestro'] != '')){
            //$maestro = Maestros::where('id','=',$request['id_maestro'])->first();
            $maestro = Maestros::findOrFail($request['id_maestro']);
            if (!empty($maestro)) {
                $i=0;
                if ((isset($request['name'])) && ($request['name'] != '') && ($request['name']!=$maestro->name)) {
                    $maestro->update(['name' => $request['name']]);
                    $i++;
                }
                if ((isset($request['lastname'])) && ($request['lastname'] != '') && ($request['lastname']!=$maestro->lastname)) {
                    $maestro->update(['lastname' => $request['lastname']]);
                    $i++;
                }
                if ((isset($request['email'])) && ($request['email'] != '') && ($request['email']!=$maestro->email)) {
                    $maestro->update(['email' => $request['email']]);
                    $maestro->api_token = null;
                    $maestro->save();
                    $i++;
                }
                if ((isset($request['identificador'])) && ($request['identificador'] != '') && ($request['identificador']!=$maestro->identificador)) {
                    $maestro->update(['identificador' => $request['identificador']]);
                    $i++;
                }
                /*
                if ((isset($request['tipo'])) && ($request['tipo'] != '') && ($request['tipo']!=$maestro->tipo)) {
                    $maestro->update(['tipo' => $request['tipo']]);
                    $i++;
                }
                if ((isset($request['tipo_str'])) && ($request['tipo_str'] != '') && ($request['tipo_str']!=$maestro->tipo_str)) {
                    $maestro->update(['tipo_str' => $request['tipo_str']]);
                    $i++;
                }
                */
                if ((isset($request['status'])) && ($request['status'] != '') && ($request['status']!=$maestro->status)) {
                    $maestro->update(['status' => $request['status']]);
                    $i++;
                }
                //especial method change password
                if ((isset($request['password'])) && ($request['password'] != '') && (!Hash::check($request['password'], $maestro->password)) ) {
                    $newPass = Hash::make($request['password']);
                    $maestro->update(['password'    => $newPass]);
                    $maestro->api_token = null;
                    $maestro->save();
                    $i++;
                }

                if ($i>0) {
                    $response = array(
                        'menssage'          => 'success',
                        'action'            => 'updateMaestro Admin',
                        'maestro'           => $maestro,
                        'codigo'            => 201
                    );
                    return response()->json($response, 201);
                }else{
                    $response = array(
                        'menssage'          => 'sin cambios',
                        'action'            => 'updateMaestro Admin',
                        'maestro'           => $maestro,
                        'codigo'            => 200
                    );
                    return response()->json($response, 200);
                }
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'updateMaestro Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_maestro es requerido.',
                'action'        => 'updateMaestro Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function updateAlumno(Request $request){
        if((isset($request['id_alumno'])) && (!empty($request['id_alumno'])) && ($request['id_alumno'] != '')){
            $alumno = Alumnos::findOrFail($request['id_alumno']);
            if (!empty($alumno)) {
                $i=0;
                if ((isset($request['name'])) && ($request['name'] != '') && ($request['name']!=$alumno->name)) {
                    $alumno->update(['name' => $request['name']]);
                    $i++;
                }
                if ((isset($request['lastname'])) && ($request['lastname'] != '') && ($request['lastname']!=$alumno->lastname)) {
                    $alumno->update(['lastname' => $request['lastname']]);
                    $i++;
                }
                if ((isset($request['email'])) && ($request['email'] != '') && ($request['email']!=$alumno->email)) {
                    $alumno->update(['email' => $request['email']]);
                    $alumno->api_token = null;
                    $alumno->save();
                    $i++;
                }
                if ((isset($request['matricula'])) && ($request['matricula'] != '') && ($request['matricula']!=$alumno->matricula)) {
                    $alumno->update(['matricula' => $request['matricula']]);
                    $i++;
                }
                /*
                if ((isset($request['tipo'])) && ($request['tipo'] != '') && ($request['tipo']!=$alumno->tipo)) {
                    $alumno->update(['tipo' => $request['tipo']]);
                    $i++;
                }
                if ((isset($request['tipo_str'])) && ($request['tipo_str'] != '') && ($request['tipo_str']!=$alumno->tipo_str)) {
                    $alumno->update(['tipo_str' => $request['tipo_str']]);
                    $i++;
                }
                */
                if ((isset($request['status'])) && ($request['status'] != '') && ($request['status']!=$alumno->status)) {
                    $alumno->update(['status' => $request['status']]);
                    $i++;
                }
                //especial method change password
                if ((isset($request['password'])) && ($request['password'] != '') && (!Hash::check($request['password'], $alumno->password)) ) {
                    $newPass = Hash::make($request['password']);
                    $alumno->update(['password'    => $newPass]);
                    $alumno->api_token = null;
                    $alumno->save();
                    $i++;
                }

                if ($i>0) {
                    $response = array(
                        'menssage'          => 'success',
                        'action'            => 'updateAlumno Admin',
                        'alumno'            => $alumno,
                        'codigo'            => 201
                    );
                    return response()->json($response, 201);
                }else{
                    $response = array(
                        'menssage'          => 'sin cambios',
                        'action'            => 'updateAlumno Admin',
                        'alumno'            => $alumno,
                        'codigo'            => 200
                    );
                    return response()->json($response, 200);
                }
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'updateAlumno Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_alumno es requerido.',
                'action'        => 'updateAlumno Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }


    public function deleteMaestro(Request $request){
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) && ($request['id_maestro'] != '')){
            $maestro = Maestros::find($request['id_maestro']);
            if (!empty($maestro)) {
                $maestro->delete();
                $response = array(
                    'menssage'          => 'Maestro eliminado correctamente',
                    'action'            => 'deleteMaestro Admin',
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'deleteMaestro Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'ID es requerido',
                'action'        => 'deleteMaestro Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function deleteAlumno(Request $request){
        if((isset($request['id_alumno'])) && (!empty($request['id_alumno'])) && ($request['id_alumno'] != '')){
            $alumno = Alumnos::find($request['id_alumno']);
            if (!empty($alumno)) {
                $alumno->delete();
                $response = array(
                    'menssage'          => 'Alumno eliminado correctamente',
                    'action'            => 'deleteAlumno Admin',
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'deleteAlumno Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'ID es requerido',
                'action'        => 'deleteAlumno Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    //Consulta - UPDATE - DELETE de Materias / Carreras
    public function getCarrera(Request $request){
        if((isset($request['id_carrera'])) && (!empty($request['id_carrera'])) && ($request['id_carrera'] != '')){
            $carrera = Carrera::where('id_carrera','=',$request['id_carrera'])->first();
            if (isset($carrera)) {
                $response = array(
                    'menssage'          => 'success',
                    'action'            => 'getCarrera Admin',
                    'carrera'            => $carrera,
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'getCarrera Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_carrera es requerido.',
                'action'        => 'getCarrera Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
    public function getMateria(Request $request){
        if((isset($request['id_materia'])) && (!empty($request['id_materia'])) && ($request['id_materia'] != '')){
            $materia = Materia::where('id_materia','=',$request['id_materia'])->first();
            if (!empty($materia)) {
                $response = array(
                    'menssage'          => 'success',
                    'action'            => 'getMateria Admin',
                    'materia'            => $materia,
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'getMateria Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_materia es requerido.',
                'action'        => 'getMateria Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
    public function getAllCarreras(Request $request){
        $carrera = Carrera::all();
        if (!empty($carrera)) {
            $response = array(
                'menssage'          => 'success',
                'action'            => 'getAllCarreras Admin',
                'carrera'           => $carrera,
                'codigo'            => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'No se encontraron registros.',
                'action'        => 'getAllCarreras Admin',
                'codigo'        => 200
            );
            return response()->json($response, 201);
        }
    }
    public function getAllMaterias(Request $request){
        $materia = Materia::all();
        if (!empty($materia)) {
            $response = array(
                'menssage'          => 'success',
                'action'            => 'getAllMaterias Admin',
                'materia'           => $materia,
                'codigo'            => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'No se encontraron registros.',
                'action'        => 'getAllMaterias Admin',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }
    }
    public function updateCarrera(Request $request){
        if((isset($request['id_carrera'])) && (!empty($request['id_carrera'])) && ($request['id_carrera'] != '')){
            $carrera = Carrera::where('id_carrera','=',$request['id_carrera'])->first();
            if (!empty($carrera)) {
                $i=0;
                if ((isset($request['carrera'])) && ($request['carrera'] != '') && ($request['carrera']!=$carrera->carrera)) {
                    $update = Carrera::where('id_carrera','=',$request['id_carrera'])->update(array('carrera' => $request['carrera']));
                    //$carrera->update(['carrera' => $request['carrera']]);
                    $i++;
                }
                if ((isset($request['no_carrera'])) && ($request['no_carrera'] != '') && ($request['no_carrera']!=$carrera->no_carrera)) {
                    $update = Carrera::where('id_carrera','=',$request['id_carrera'])->update(array('no_carrera' => $request['no_carrera']));
                    //$carrera->update(['no_carrera' => $request['no_carrera']]);
                    $i++;
                }

                if ($i>0) {
                    $response = array(
                        'menssage'          => 'success',
                        'action'            => 'updateCarrera Admin',
                        'carrera'            => $carrera,
                        'codigo'            => 201
                    );
                    return response()->json($response, 201);
                }else{
                    $response = array(
                        'menssage'          => 'sin cambios',
                        'action'            => 'updateCarrera Admin',
                        'carrera'            => $carrera,
                        'codigo'            => 200
                    );
                    return response()->json($response, 200);
                }
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'updateCarrera Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_carrera es requerido.',
                'action'        => 'updateCarrera Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
    public function updateMateria(Request $request){
        if((isset($request['id_materia'])) && (!empty($request['id_materia'])) && ($request['id_materia'] != '')){
            $materia = Materia::where('id_materia','=',$request['id_materia'])->first();
            if (!empty($materia)) {
                $i=0;
                if ((isset($request['nombre'])) && ($request['nombre'] != '') && ($request['nombre']!=$materia->nombre)) {
                    $update = Materia::where('id_materia','=',$request['id_materia'])->update(array('nombre' => $request['nombre']));
                    //$materia->update(['nombre' => $request['nombre']]);
                    $i++;
                }
                if ((isset($request['cred'])) && ($request['cred'] != '') && ($request['cred']!=$materia->cred)) {
                    $update = Materia::where('id_materia','=',$request['id_materia'])->update(array('cred' => $request['cred']));
                    //$materia->update(['cred' => $request['cred']]);
                    $i++;
                }

                if ($i>0) {
                    $response = array(
                        'menssage'          => 'success',
                        'action'            => 'updateMateria Admin',
                        'materia'            => $materia,
                        'codigo'            => 201
                    );
                    return response()->json($response, 201);
                }else{
                    $response = array(
                        'menssage'          => 'sin cambios',
                        'action'            => 'updateMateria Admin',
                        'materia'            => $materia,
                        'codigo'            => 200
                    );
                    return response()->json($response, 200);
                }
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'updateMateria Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo id_materia es requerido.',
                'action'        => 'updateMateria Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
    public function deleteCarrera(Request $request){
        if((isset($request['id_carrera'])) && (!empty($request['id_carrera'])) && ($request['id_carrera'] != '')){
            $carrera =  Carrera::where('id_carrera','=',$request['id_carrera'])->first();
            if (!empty($carrera)) {
               $delete = Carrera::where('id_carrera','=',$request['id_carrera'])->delete();
                $response = array(
                    'menssage'          => 'Carrera eliminada correctamente',
                    'action'            => 'deleteCarrera Admin',
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'deleteCarrera Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'ID es requerido',
                'action'        => 'deleteCarrera Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
    public function deleteMateria(Request $request){
        if((isset($request['id_materia'])) && (!empty($request['id_materia'])) && ($request['id_materia'] != '')){
            $materia = Materia::where('id_materia','=',$request['id_materia'])->first();
            if (!empty($materia)) {
                $delete = Materia::where('id_materia','=',$request['id_materia'])->delete();
                $response = array(
                    'menssage'          => 'Materia eliminada correctamente',
                    'action'            => 'deleteMateria Admin',
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontro registro para este ID.',
                    'action'        => 'deleteMateria Admin',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'ID es requerido',
                'action'        => 'deleteMateria Admin',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

}
