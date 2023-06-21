<?php

namespace App\Http\Middleware;

use Closure;
use App\User as User;
use App\Alumnos as Alumnos;
use App\Maestros as Maestros;

class AlumnosAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        if (isset($token) && isset($request['id_alumno'])) {
            $alumno = User::where('id','=',$request['id_alumno'])->where('api_token','=',$token)->first();
            if ($alumno) {
                return $next($request);
            }else{
                $response = array(
                    'menssage'      => 'Inicia sesión de nuevo.',
                    'codigo'        => 400
                );
                return response()->json($response, 400);
            }
        }else{
            $response = array(
                'menssage'      => 'La petición es sistemáticamente incorrecta.',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }
}
