<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProfesoresMateriasGrupos as PMG;
use App\MateriasTmp as MateriasTMP;
use App\AlumnosTmp as AlumnosTmp;
use App\Kardex as Kardex;
use App\CalificacionesTmp as CalificacionesTmp;
use App\CalificacionesBitacora as CalificacionesBit;
use App\Maestros as Profesores;
use App\Periodos as Periodo;




class maestrosController extends Controller
{


    public function getGrupos(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) && ($request['id_maestro'] != '')){

            //$grupos = PMG::select('grupo')->distinct()->get();
            $maestro = Profesores::where('id','=',$request['id_maestro'])->select("identificador")->first();
            $grupos = PMG::where('Identificador_Profesor','=',$maestro['identificador'])->select("grupo")->get();
            //dump($grupos);
            if (!empty($grupos)) {
                $grupos_distinct = array ();
                foreach ($grupos as $grupo) {
                    array_push($grupos_distinct, $grupo['grupo']);
                }
                sort($grupos_distinct);
                $response = array(
                    'menssage'      => 'success',
                    'action'        => 'getGrupos',
                    'grupos'         => $grupos_distinct,
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No hay grupos disponibles para este Profesor.',
                    'action'        => 'getGrupos',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo Profesor Id es requerido.',
                'action'        => 'getGrupos',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getMaterias(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) && ($request['id_maestro'] != '')){

            $maestro = Profesores::where('id','=',$request['id_maestro'])->select("identificador")->first();
            $materiasProfesor = PMG::where('Identificador_Profesor','=',$maestro['identificador'])->select("clave_materia")->get();
            $x = 0;
            
            foreach($materiasProfesor as $materiaTMP){
                $materias = MateriasTMP::where('id_materia','=',$materiaTMP['clave_materia'])->select("id_materia", "nombre", "carrera")->get();
                if (sizeof($materias) > 0){
                    $JsonMateria[$x]= array(
                        'id_materia' => $materias[0]['id_materia'],
                        'nombre' => $materias[0]['nombre'],
                        'carrera' =>  $materias[0]['carrera']
                       
                    );
                    $x++;
                }
            }
            //dump($JsonMateria);
            //$materias = MateriasTMP::whereIn('id_materia',$materiasProfesor['clave_materia'])->select("id_materia", "nombre", "carrera")->get();

            if (!empty($JsonMateria)) {
                $response = array(
                    'menssage'      => 'success',
                    'action'        => 'getMaterias',
                    'materias'      => $JsonMateria,
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No hay materias para este Profesor.',
                    'action'        => 'getMaterias',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'El campo Profesor Id es requerido.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getAlumnosByGrupo(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) 
            && (isset($request['grupoId'])) && (!empty($request['grupoId']))
        ){

            //$alumnos = AlumnosTmp::select('matricula', 'nombre_alumno', 'apellidop_alumno', 'apellidom_alumno', 'carrera' ,'semestre', 'grupo')->get();
            $alumnos = AlumnosTmp::where('grupo','=',$request['grupoId'])->get();

            if (!empty($alumnos)) {
                $response = array(
                    'menssage'      => 'success',
                    'action'        => 'getAlumnosByGrupo',
                    'alumnos'       => $alumnos,
                    'codigo'        => 200
                );
            }else{
                $response = array(
                    'menssage'      => 'No hay alumnos para este grupo.',
                    'action'        => 'getAlumnosByGrupo',
                    'codigo'        => 200
                );
            }

            
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'El campo Profesor Id es requerido.',
                'action'        => 'getAlumnosByGrupo',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getAlumnosByGrupoAndMateria(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) 
            && (isset($request['grupoId'])) && (!empty($request['grupoId'])) 
            && (isset($request['materiaId'])) && (!empty($request['materiaId']))
        ){
            $response = array(
                'menssage'      => 'No hay alumnos para este grupo y materia.',
                'action'        => 'getMaterias',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'El campo Profesor Id es requerido.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function setCalificacion(Request $request)
    {
        if(
            (isset($request['id_maestro'])) && (!empty($request['id_maestro'])) 
            && (isset($request['grupoId'])) && (!empty($request['grupoId'])) 
            && (isset($request['materiaId'])) && (!empty($request['materiaId']))
            && (isset($request['alumnoMatricula'])) && (!empty($request['alumnoMatricula']))
            && (isset($request['calificacionInt'])) && (!empty($request['calificacionInt']))
            && (isset($request['tipoCal'])) && (!empty($request['tipoCal']))
            && (isset($request['periodo'])) && (!empty($request['periodo']))
        ){
            //set calificaciones
            $periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();

            $calInsert = CalificacionesTmp::create([
                'id_maestro'        => $request['id_maestro'],
                'grupoId'           => $request['grupoId'],
                'materiaId'         => $request['materiaId'],
                'alumnoMatricula'   => $request['alumnoMatricula'],
                'calificacionInt'   => $request['calificacionInt'],
                'tipoCal'           => $request['tipoCal'],
                'periodo'           => $periodo_activo['periodo_evaluacion'],
            ]);

            //tabla de bitacora
            CalificacionesBit::create([
                'id_calificacion'       => $calInsert->id,
                'alumno_matricula'      => $request['alumnoMatricula'],//--->
                'id_user'               => $request['id_maestro'],//--->
                'user_type'             => 'MAESTRO',
                'accion'                => 'CREATE',
                'calificacion_after'   => $calInsert,
            ]);

            $response = array(
                'menssage'      => 'success',
                'action'        => 'setCalificacion',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function getMyCalificaciones(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro']))){
            $calificaciones = CalificacionesTmp::where('id_maestro','=',$request['id_maestro'])->get();
            /*
            if (isset() $$ !empty() && != '' ) {
                // code...
            }
            */
            if ((!empty($calificaciones)) && (count($calificaciones)>0)) {
                foreach ($calificaciones as $_calificacion) {
                    $alumno = AlumnosTmp::where('matricula','=',$_calificacion['alumnoMatricula'])->first();

                    if (!empty($alumno)) {
                        $_alumno = $alumno;
                        $_calificacion['alumno'] = $_alumno;
                    } else {
                        $_alumno = array();
                        $_calificacion['alumno'] = $_alumno;
                    }
                }
                $response = array(
                    'menssage'          => 'success',
                    'action'            => 'getCalificaciones',
                    'calificaciones'    => $calificaciones,
                    'codigo'            => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'No se encontraron calificaciones registradas para este Docente.',
                    'action'        => 'getMaterias',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'getMyCalificaciones',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function updateCalificacion(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) 
            && (isset($request['grupoId'])) && (!empty($request['grupoId'])) 
            && (isset($request['materiaId'])) && (!empty($request['materiaId']))
            && (isset($request['alumnoMatricula'])) && (!empty($request['alumnoMatricula']))
            && (isset($request['calificacionInt'])) && (!empty($request['calificacionInt']))
            && (isset($request['tipoCal'])) && (!empty($request['tipoCal']))
        ){


            /*

            //tabla de bitacora
            CalificacionesBit::create([
                'id_calificacion'       => $calInsert->id,
                'alumno_matricula'      => $request['alumnoMatricula'],//--->
                'id_user'               => $request['id_maestro'],//--->
                'user_type'             => $request['alumnoMatricula'],
                'accion'                => 'CREATE',
                'calificacion_before'   => $calInsert,
            ]);

            */




            $response = array(
                'menssage'      => 'No se pudo asignar la calificación.',
                'action'        => 'getMaterias',
                'codigo'        => 200
            );
            return response()->json($response, 200);
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }

    public function deleteCalificacion(Request $request)
    {
        if((isset($request['id_maestro'])) && (!empty($request['id_maestro'])) 
            && (isset($request['id_calificacion'])) && (!empty($request['id_calificacion'])) 
        ){
            $searchCalToDelete = CalificacionesTmp::where('id','=',$request['id_calificacion'])->first();
            if ($searchCalToDelete) {
                $calToDelete = CalificacionesTmp::find($request['id_calificacion'])->delete();
                //tabla de bitacora
                CalificacionesBit::create([
                    'id_calificacion'       => $request['id_calificacion'],//--->
                    'alumno_matricula'      => $searchCalToDelete->alumnoMatricula,
                    'id_user'               => $request['id_maestro'],//--->
                    'user_type'             => 'MAESTRO',
                    'accion'                => 'DELETE',
                    'calificacion_before'   => $searchCalToDelete,
                    'calificacion_after'    => NULL,
                ]);
                $response = array(
                    'menssage'      => 'success',
                    'action'        => 'deleteCalificacion',
                    'codigo'        => 200
                );
                return response()->json($response, 200);
            }else{
                $response = array(
                    'menssage'      => 'Error al borrar (no existe el id).',
                    'action'        => 'deleteCalificacion',
                    'codigo'        => 404
                );
                return response()->json($response, 404);
            }
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
}
