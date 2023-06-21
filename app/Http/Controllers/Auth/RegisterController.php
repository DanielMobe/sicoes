<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Maestros;
use App\Admins;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $request){
        $validateUserToUser = Validator::make($request, [
            'name'          => ['required', 'string', 'max:50'],
            'lastname'      => ['required', 'string', 'max:50'],
            'email'         => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'matricula'     => ['required', 'string', 'max:50', 'unique:users'],
            'password'      => ['required', 'string', 'min:8'],
        ]);
        return $validateUserToUser;
    }

    protected function validatorMaestro(array $request){
        $validateUserToUser = Validator::make($request, [
            'name'              => ['required', 'string', 'max:50'],
            'lastname'          => ['required', 'string', 'max:50'],
            'email'             => ['required', 'string', 'email', 'max:50', 'unique:maestros'],
            'password'          => ['required', 'string', 'min:8'],
        ]);
        return $validateUserToUser;
    }

    protected function validatorAdmin(array $request){
        $validateUserToUser = Validator::make($request, [
            'name'              => ['required', 'string', 'max:50'],
            'lastname'          => ['required', 'string', 'max:50'],
            'email'             => ['required', 'string', 'email', 'max:50', 'unique:admins'],
            'password'          => ['required', 'string', 'min:8'],
        ]);
        return $validateUserToUser;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
         /* tipo
         * 0 - baja
         * 1 - alumno
         * 2 - prospecto
         * 3 - egresado
         */

        /* status
         * 0 - inactivo
         * 1 - activo
         */

        return User::create([
            'name'          => $data['name'],
            'lastname'      => $data['lastname'],
            'email'         => $data['email'],
            'matricula'     => $data['matricula'],
            'password'      => Hash::make($data['password']),
            'tipo'          => 1,
            'tipo_str'      => 'alumno',
            'status'        => 1,
        ]);
    }

    protected function createMaestro(array $data)
    {
         /* tipo
         * 5 - maestro
         * 6 - admin
         */

        /* status
         * 0 - inactivo
         * 1 - activo
         */

        return Maestros::create([
            'name'              => $data['name'],
            'lastname'          => $data['lastname'],
            'email'             => $data['email'],
            'identificador'     => 1,
            'password'          => Hash::make($data['password']),
            'tipo'              => 5,
            'tipo_str'          => 'maestro',
            'status'            => 1,
        ]);
    }

    protected function createAdmin(array $data)
    {
         /* tipo
         * 5 - maestro
         * 6 - admin
         */

        /* status
         * 0 - inactivo
         * 1 - activo
         */

        return Admins::create([
            'name'              => $data['name'],
            'lastname'          => $data['lastname'],
            'email'             => $data['email'],
            'identificador'     => 1,
            'password'          => Hash::make($data['password']),
            'tipo'              => 6,
            'tipo_str'          => 'admin',
            'status'            => 1,
        ]);
    }

    public function register(Request $request)
    {
        //validate user info from request
        $validate = $this->validator($request->all());

        //check error on validation
        if ($validate->fails()){
            //return $validate->errors();
            return response()->json(['errors' => $validate->errors()], 400);
        }

        //manda el request a crear el usuario
        $user = $this->create($request->all());

        // set user loged in
        $this->guard()->login($user);

        return $this->registered($request, $user);
    }

    protected function registered(Request $request, $user)
    {
        
        $user->generateToken();

        return response()->json(['user' => $user->toArray()], 201);
    }

    public function registerMaestro(Request $request)
    {
        $validate = $this->validatorMaestro($request->all());

        if ($validate->fails()){
            //return $validate->errors();
            return response()->json(['errors' => $validate->errors()], 400);
        }

        $maestro = $this->createMaestro($request->all());

        //$this->guard()->login($maestro);

        return $this->registeredMaestro($request, $maestro);
    }

    protected function registeredMaestro(Request $request, $maestro)
    {
        
        $maestro->generateToken();

        return response()->json(['maestro' => $maestro->toArray()], 201);
    }






    public function registerAdmin(Request $request)
    {
        $validate = $this->validatorAdmin($request->all());

        if ($validate->fails()){
            //return $validate->errors();
            return response()->json(['errors' => $validate->errors()], 400);
        }

        $admin = $this->createAdmin($request->all());

        //$this->guard()->login($admin);

        return $this->registeredAdmin($request, $admin);
    }

    protected function registeredAdmin(Request $request, $admin)
    {
        
        $admin->generateToken();

        return response()->json(['admin' => $admin->toArray()], 201);
    }
}
