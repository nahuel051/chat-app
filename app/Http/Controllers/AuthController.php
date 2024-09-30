<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showAuthForm(){
        return view('auth');
    }
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
        ]);
        // return redirect()->route('auth');
        //Devuelve uan respuesta JSON de exito.
        if($request->ajax()){
            return response()->json(['success'=>true]);
        }
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            // return redirect()->route('index');
            return response()->json(['success' => true]);
        }
        // return back()->withErrors(['error' => 'Credenciales erroneas!']);
        return response()->json(['errors' => ['email' => ['Las credenciales no coinciden con nuestros registros.']]], 422);
    }

    //Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth');
    }
}
