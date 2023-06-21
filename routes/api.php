<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:maestros:api')->get('/maestros', function (Request $request) {
    return $request->user();
});

//rutas de alumnos
Route::group(['middleware' => ['alumnos_auth']], function () {
    Route::get('alumno',             'alumnosController@index');
    Route::get('alumno/{alumno}',    'alumnosController@show');
    Route::post('alumno',            'alumnosController@store');
    Route::put('alumno/{alumno}',    'alumnosController@update');
    Route::delete('alumno/{alumno}', 'alumnosController@delete');
    //rutas para kardex y boleta
    Route::post('alumno/kardex',     'alumnosController@getKardex');
    Route::post('alumno/boleta',     'alumnosController@getBoleta');
    Route::post('alumno/calificaciones',     'alumnosController@getCalificacion');

    //validar calificaiones
    Route::post('alumno/validar', 'alumnosController@ValidarCalf');

    //actualizar datos complementarios
    Route::post('alumno/datosComplementarios', 'alumnosController@updateAlumno');
    
});

//rutas de maestros
Route::group(['middleware' => ['maestros_auth']], function () {
    Route::post('maestro/getGrupos',                        'maestrosController@getGrupos');
    Route::post('maestro/getMaterias',                      'maestrosController@getMaterias');
    Route::post('maestro/getAlumnosByGrupo',                'maestrosController@getAlumnosByGrupo');
    Route::post('maestro/getAlumnosByGrupoAndMateria',      'maestrosController@getAlumnosByGrupoAndMateria');
    Route::post('maestro/setCalificacion',                  'maestrosController@setCalificacion');
    //Route::post('maestro/getCalificacion',                  'maestrosController@getCalificacion');
    Route::post('maestro/updateCalificacion',               'maestrosController@updateCalificacion');
    Route::post('maestro/deleteCalificacion',               'maestrosController@deleteCalificacion');

    Route::post('maestro/getMyCalificaciones',                  'maestrosController@getMyCalificaciones');

});

//login auth alumno
Route::post('alumno/register', 'Auth\RegisterController@register');
Route::post('alumno/login',    'Auth\LoginController@login');
Route::post('alumno/logout',   'Auth\LoginController@logout');

//login auth maestros
Route::post('maestro/register', 'Auth\RegisterController@registerMaestro');
Route::post('maestro/login',    'Auth\LoginController@loginMaestro');
Route::post('maestro/logout',   'Auth\LoginController@logoutMaestro');

//login auth de admins
Route::post('admin/register', 'Auth\RegisterController@registerAdmin');
Route::post('admin/login',    'Auth\LoginController@loginAdmin');
Route::post('admin/logout',   'Auth\LoginController@logoutAdmin');

//login required
Route::get('login/required', 'alumnosController@loginRequired');

//recuperar pasword 
Route::post('util/recover/password', 'RecuperarPasswordController@recuperarPassword');
Route::post('util/reset/password', 'RecuperarPasswordController@resetPasswoCode');

Route::post('util/getMaterias', 'UtilController@getMaterias');
//bitacora routes
Route::group(['middleware' => ['admins_auth']], function () {
    Route::post('admin/bitacora/calificaciones', 'BitacoraController@getCalificacionesBitacora');
    
    Route::post('admin/getMaestro',             'AdminsController@getMaestro');//singular by id
    Route::post('admin/getAlumno',              'AdminsController@getAlumno');//singular by id
    Route::post('admin/getAllMaestros',         'AdminsController@getAllMaestros');//all collection
    Route::post('admin/getAllAlumnos',          'AdminsController@getAllAlumnos');//all collection

    Route::post('admin/updateMaestro',             'AdminsController@updateMaestro');
    Route::post('admin/updateAlumno',              'AdminsController@updateAlumno');

    Route::post('admin/deleteMaestro',             'AdminsController@deleteMaestro');
    Route::post('admin/deleteAlumno',              'AdminsController@deleteAlumno');
    
    //Rutas admin periodos
    Route::post('admin/storePeriodo',              'periodosController@store');
    Route::post('admin/activarPeriodo',            'periodosController@ActivarPeriodo');
    Route::post('admin/deletePeriodo',             'periodosController@delete');
    Route::post('admin/consultaPeriodos',             'periodosController@consultaPeriodo');

    //Rutas Materias - Carreras
    Route::post('admin/getCarrera',             'AdminsController@getCarrera');
    Route::post('admin/getMateria',             'AdminsController@getMateria');
    Route::post('admin/getAllCarreras',         'AdminsController@getAllCarreras');
    Route::post('admin/getAllMaterias',         'AdminsController@getAllMaterias');

    Route::post('admin/updateCarrera',          'AdminsController@updateCarrera');
    Route::post('admin/updateMateria',          'AdminsController@updateMateria');

    Route::post('admin/deleteCarrera',          'AdminsController@deleteCarrera');
    Route::post('admin/deleteMateria',          'AdminsController@deleteMateria');
    
});

