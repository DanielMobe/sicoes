<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Maestros;
use App\Admins;
use Hash;
use Session;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            //generar token
            $user->generateToken();

            return response()->json([
                'user' => $user->toArray(),
            ],200);
        }else{
            return response()->json([
                'errors' => 'Usuario o Contraseña Incorrectos',
            ],400);
        }
    }

    public function loginMaestro(Request $request)
    {
        $this->validateLogin($request);

        $email = $request['email'];
        $pass = Hash::make($request['password']);
        //Hash::check($request->newPasswordAtLogin, $hashedPassword);
        $maestro = Maestros::where("email","=",$email)->first();

        if (!empty($maestro)) {         
            if (Hash::check($request['password'], $maestro->password)) {
                $maestro->generateToken();
                return response()->json(['maestro' => $maestro->toArray(),],200);
            }else{
                return response()->json(['errors' => 'Usuario o Contraseña Incorrectos',],400);
            }
        }else{
            return response()->json(['errors' => 'Usuario o Contraseña Incorrectos',],400);
        }
    }

    public function loginAdmin(Request $request)
    {
        $this->validateLogin($request);

        $email = $request['email'];
        $pass = Hash::make($request['password']);
        //Hash::check($request->newPasswordAtLogin, $hashedPassword);
        $admin = Admins::where("email","=",$email)->first();

        if (!empty($admin)) {         
            if (Hash::check($request['password'], $admin->password)) {
                $admin->generateToken();
                return response()->json(['admin' => $admin->toArray(),],200);
            }else{
                return response()->json(['errors' => 'Usuario o Contraseña Incorrectos',],400);
            }
        }else{
            return response()->json(['errors' => 'Usuario o Contraseña Incorrectos',],400);
        }
    }

    public function logout(Request $request)
    {
        $user = User::find($request['id']);

        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json(['data' => 'User logged out.'], 200);
        }else{
            return response()->json(['errors' => "User don't exist."], 400);
        }
    }

    public function logoutMaestro(Request $request)
    {
        $maestro = Maestros::find($request['id']);

        if ($maestro) {
            $maestro->api_token = null;
            $maestro->save();
            return response()->json(['data' => 'User logged out.'], 200);
        }else{
            return response()->json(['errors' => "User don't exist."], 400);
        }
    }

    public function logoutAdmin(Request $request)
    {
        $admin = Admins::find($request['id']);

        if ($admin) {
            $admin->api_token = null;
            $admin->save();
            return response()->json(['data' => 'User logged out.'], 200);
        }else{
            return response()->json(['errors' => "User don't exist."], 400);
        }
    }
}
