<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class MainController extends Controller
{
    public function loginindex(){
        return view('login');
    }
    public function registerindex(){
        return view('register');
    }
    public function register(Request $request){


        $request->validate([
            'prenom' => 'required',
            'nom' => 'required',
            'email' => 'required|email|unique:users',
            'roles' => 'required',
            'password' => 'required|min:5',
            'password2' => 'required|min:5', 
        ]);

        if($request->password == $request->password2){
            $newUser =  new User;

            $newUser->nom = $request->nom;
            $newUser->prenom = $request->prenom;
            $newUser->email = $request->email;
            $newUser->role = $request->roles;
            $newUser->password = Hash::make($request->password);

            $newUser->save();

            if($newUser){
                return back()->with('success', 'The  user is registered successfully');
            } else {
                return back()->with('fail', 'Error occured');
            }

        }
        
        
    }
    public function logout(Request $request){
        if(Auth::check()){
            Auth::logout();
            return redirect('/login');
        }
    }
}
