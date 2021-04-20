<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/','welcome');


Auth::routes();
Route::view('/login','login')->name('loginpage');
Route::view('/register','register');
Route::post('/logout', function(){
    Auth::logout();
    return Redirect::to('/login');
 })->name('logout');

Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::post('/register',[MainController::class, 'register'])->name('register');


/* This route group is where the authenticated users go to */
Route::group(['middleware' => 'auth'], function(){

    // Route group for admin
    Route::group(['prefix' => 'admin', 'as' => 'admin'], function(){
        Route::view('/dashboard', 'admin.master');
    });
    // Route group for etudiant
    Route::group(['prefix' => 'etudiant', 'as' => 'etudiant'], function(){
        Route::view('/dashboard', 'etudiant.master');
    });
    // Route group for Professeur
    Route::group(['prefix' => 'ens', 'as' => 'prof'], function(){
        Route::view('/dashboard', 'prof.master');
    });
    // Route group for service de scolarite
    Route::group(['prefix' => 'service_de_scolarite', 'as' => 'ess'], function(){
        Route::view('/dashboard', 'ess.master');
    });
    // Route group for service d'examen
    Route::group(['prefix' => 'service_examen', 'as' => 'ese'], function(){
        Route::view('/dashboard', 'ese.master');
    });
    
});





//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
