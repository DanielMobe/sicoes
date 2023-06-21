<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maestros as Maestros;
use App\User as Alumnos;
use App\Admins as Admins;
use App\Mail\RecuperarPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;


class RecuperarPasswordController extends Controller
{
    public function recuperarPassword(Request $request)
    {
        if( (isset($request['tipo_user'])) && ($request['tipo_user'] != '') 
            && (isset($request['email'])) && ($request['email'] != '') )
        {
            //tipos = alumno maestro admin
            $exist = false;
            $emailSend = false;

            switch ($request['tipo_user']) {
                case "alumno":
                    $alumno = Alumnos::where('email','=',$request['email'])->first();
                    if (!empty($alumno)) {
                        $exist              = true;
                        $sicoesURL          = env('MAIL_SICOES_URL');
                        $recuUrl            = env('MAIL_RECU_URL');
                        $codigo             = "recuperarPassword".str_random(40);
                        $alumno->api_token  = $codigo;
                        $alumno->save();
                        $data               = array(
                            'codigo'       => $codigo,
                            'tipo'         => $request['tipo_user']
                        );
                        $alumno->recuUrl    = $recuUrl."?data=".base64_encode(json_encode($data));
                        $alumno->sicoesUrl  = $sicoesURL;
                        Mail::to($alumno->email)->send(new RecuperarPassword($alumno));
                        $emailSend = true;
                    }

                    break;
                case "maestro":
                    $maestro = Maestros::where('email','=',$request['email'])->first();
                    if (!empty($maestro)) {
                        $exist = true;
                        $sicoesURL              = env('MAIL_SICOES_URL');
                        $recuUrl                = env('MAIL_RECU_URL');
                        $codigo                 = "recuperarPassword".str_random(40);
                        $maestro->api_token     = $codigo;
                        $maestro->save();
                        $data                   = array(
                            'codigo'           => $codigo,
                            'tipo_user'        => $request['tipo_user']
                        );
                        $maestro->recuUrl       = $recuUrl."?data=".base64_encode(json_encode($data));
                        $maestro->sicoesUrl     = $sicoesURL;
                        Mail::to($maestro->email)->send(new RecuperarPassword($maestro));
                        $emailSend = true;
                    }

                    break;
                case "admin":
                    $admin = Admins::where('email','=',$request['email'])->first();
                    if (!empty($admin)) {
                        $exist = true;
                        $sicoesURL            = env('MAIL_SICOES_URL');
                        $recuUrl              = env('MAIL_RECU_URL');
                        $codigo               = "recuperarPassword".str_random(40);
                        $admin->api_token     = $codigo;
                        $admin->save();
                        $data                 = array(
                            'codigo'         => $codigo,
                            'tipo_user'      => $request['tipo_user']
                        );
                        $admin->recuUrl       = $recuUrl."?data=".base64_encode(json_encode($data));
                        $admin->sicoesUrl     = $sicoesURL;
                        Mail::to($admin->email)->send(new RecuperarPassword($admin));
                        $emailSend = true;
                    }
                    break;
            }

            $response = array(
                'menssage'      => 'Si la cuenta existe enviaremos un email de recuperación',
                'action'        => 'recuperarPassword',
                'estatus'       => $exist,
                'emailSend'     => $emailSend,
                'codigo'        => 200
            );

            return response()->json($response, 200);
        
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'recuperarPassword',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }


    public function resetPasswoCode(Request $request)
    {
        if( (isset($request['tipo_user'])) && ($request['tipo_user'] != '')
            && (isset($request['code'])) && ($request['code'] != '')
            && (isset($request['pwdN'])) && ($request['pwdN'] != '')
        ){
            $applyChange    = false;
            $changePassword = false;
            $menssage       = "petición invalida";
            $httpStatus     = 400;

            switch ($request['tipo_user']) {
                case "alumno":
                    $alumno = Alumnos::where('api_token','=',$request['code'])->first();
                    if (!empty($alumno)) {
                        $applyChange        = true;
                        $alumno->password   = Hash::make($request['pwdN']);
                        $alumno->api_token  = null;
                        $alumno->save();
                        $changePassword     = true;
                        $httpStatus         = 200;
                        $menssage           = "se ha cambiado la contraseña";
                    }else{
                        $menssage           = "solicite la recuperación de contraseña de nuevo";
                    }
                    break;
                case "maestro":
                    $maestro = Maestros::where('api_token','=',$request['code'])->first();
                    if (!empty($maestro)) {
                        $applyChange            = true;
                        $maestro->password      = Hash::make($request['pwdN']);
                        $maestro->api_token     = null;
                        $maestro->save();
                        $changePassword         = true;
                        $httpStatus             = 200;
                        $menssage               = "se ha cambiado la contraseña";
                    }else{
                        $menssage               = "solicite la recuperación de contraseña de nuevo";
                    }
                    break;
                case "admin":
                    $admin = Admins::where('api_token','=',$request['code'])->first();
                    if (!empty($admin)) {
                        $applyChange        = true;
                        $admin->password    = Hash::make($request['pwdN']);
                        $admin->api_token   = null;
                        $admin->save();
                        $changePassword     = true;
                        $httpStatus         = 200;
                        $menssage           = "se ha cambiado la contraseña";
                    }else{
                        $menssage           = "solicite la recuperación de contraseña de nuevo";
                    }
                    break;
            }
            $response = array(
                'menssage'          => $menssage,
                'action'            => 'resetPasswoCode',
                'applyChange'       => $applyChange,
                'changePassword'    => $changePassword,
                'codigo'            => $httpStatus
            );
            return response()->json($response, $httpStatus);

        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'resetPasswoCode',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }



}
