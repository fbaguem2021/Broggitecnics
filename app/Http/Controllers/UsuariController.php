<?php

namespace App\Http\Controllers;

use App\Models\Usuari;
use Illuminate\Http\Request;
use App\Http\Controllers\Utilitat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UsuariController extends Controller
{
    public function showLogin()
    {
        // $usuario = new Usuario();
        // $usuario->username='jnirella';
        // $usuario->contrasenya=\bcrypt('123456');
        // $usuario->nom='Jose';
        // $usuario->cognoms="Nirella Munoz";
        // $usuario->tipus_usuaris_id=1;
        // $usuario->save();
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $username = $request->input('userName');
        $pass = $request->input('password1');

        $user = Usuari::where('username', $username)->first();

        if ($user != null && Hash::check($pass, $user->contrasenya)) {
            Auth::login($user);
            $response = redirect('/home')->cookie('username', $username, 60*24*7, null, null, false, false);
        } else {
            $request->session()->flash('error', 'Usuari o contrasenya incorrectes');
            $response = redirect('/login')->withInput();
        }
        return $response;
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showRegistre(){
        return view('auth.newUser');
    }

    public function store(Request $request){
        $usuario = new Usuari();
        $strNom=$request->input('nom');
        $strCogn=$request->input('cognoms');
        
        $usuario->nom = $strNom;
        $usuario->cognoms= $strCogn;
        //crear nombre usuario con la primera letra del nombre y el primer apellido
        $first_character = mb_substr($strNom, 0, 1);
        $array = explode(" ",$strCogn);
        $first_cognom = $array[0];
        $usuario->username = strtolower($first_character.$first_cognom);
        $usuario->contrasenya=\bcrypt($request->input('pass'));       
        $usuario->tipus_usuaris_id=1;
        
        
        try {
            $usuario->save();
            $response = redirect('/login')->withInput(['userName'=> strtolower($first_character.$first_cognom)]);
        } catch (QueryException $ex) {
            $mensaje = Utilitat::errorMessage($ex);
            $request->session()->flash('error', $mensaje);
            $response = redirect()->action([UsuariController::class, 'showRegistre'])->withInput();
            
            
        }
        return $response;
        
    }
}
