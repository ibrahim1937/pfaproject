<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        
        $user = User::where('email','=',$request->email)->first();

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){
            Auth::login(User::find($user->id), true);
            // TODO enter users by roles
            if($user->role == 'admin'){
                return redirect('/admin/dashboard');
            } elseif($user->role == 'etudiant'){
                return redirect('/etudiant/dashboard');
            }
            elseif($user->role == 'professeur'){
                return redirect('/ens/dashboard');
            }
            elseif($user->role == 'ess'){
                return redirect('/service_de_scolarite/dashboard');
            }
            elseif($user->role == 'ese'){
                return redirect('/service_examen/dashboard');
            }
            
        } else {
            return back()->with('fail','Veuillez saisir des informations correctes');
        }



    }
}
