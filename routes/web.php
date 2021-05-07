<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MainController;
use App\Mail\StyledMail;

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
Route::get('/email', function(){
    return new StyledMail();
});

Route::get('/send-email', [MailController::class, 'sendmail']);



Auth::routes();
Route::view('/login','login')->name('loginpage');
Route::get('/register',[MainController::class, 'registerindex']);
Route::post('/logout', function(){
    Auth::logout();
    return Redirect::to('/login');
 })->name('logout');

Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::post('/register',[MainController::class, 'register'])->name('register');
Route::get('/routing',[MainController::class, 'routing'])->name('route');


/* This route group is where the authenticated users go to */
Route::group(['middleware' => 'auth'], function(){

    // Route group for admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function(){
        // all views
        Route::view('/dashboard', 'admin.pages.dashboard')->name('dashboard');
        Route::get('/gestionEtudiant', [AdminController::class , 'etudiant'])->name('etudiant');
        Route::view('/gestionfiliere', 'admin.pages.filiere')->name('filiere');
        Route::get('/gestionProfesseur', [AdminController::class, 'professeur'])->name('professeur');
        Route::view('/gestionAgentScolarite', 'admin.pages.agentscolarite')->name('agentscolarite');
        Route::view('/gestionAgentExamen', 'admin.pages.agentexamen')->name('agentexamen');
        Route::get('/gestionelement', [AdminController::class, 'element'])->name('element');
        Route::get('/gestionmodule', [AdminController::class, 'module'])->name('module');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::get('/exportsample', [AdminController::class, 'exportsample'])->name('exportsample');
        Route::post('/import', [AdminController::class, 'import'])->name('import');

        // post 
        Route::post('/gestionfiliere', [AdminController::class, 'gestionfiliere'])->name('gestionfiliere');
        Route::post('/gestionmodule', [AdminController::class, 'gestionModule'])->name('gestionmodule');
        Route::post('/gestionelement', [AdminController::class, 'gestionElement'])->name('gestionelement');
        Route::post('/gestionProfesseur', [AdminController::class, 'gestionProfesseur'])->name('gestionprofesseur');
        Route::post('/gestionAgentScolarite', [AdminController::class, 'gestionAgentScolarite'])->name('gestionagentscolarite');
        Route::post('/gestionAgentExamen', [AdminController::class, 'gestionAgentExamen'])->name('gestionagentexamen');
        Route::post('/gestionEtudiant', [AdminController::class , 'gestionetudiant'])->name('gestionetudiant');
    });
    // Route group for etudiant
    Route::group(['prefix' => 'etudiant', 'as' => 'etudiant.'], function(){
        Route::view('/dashboard', 'etudiant.master')->name('dashboard');
    });
    // Route group for Professeur
    Route::group(['prefix' => 'prof', 'as' => 'prof.'], function(){
        Route::view('/dashboard', 'prof.master')->name('dashboard');
    });
    // Route group for service de scolarite
    Route::group(['prefix' => 'service_de_scolarite', 'as' => 'ess.'], function(){
        Route::view('/dashboard', 'ess.master')->name('dashboard');
    });
    // Route group for service d'examen
    Route::group(['prefix' => 'service_examen', 'as' => 'ese.'], function(){
        Route::view('/dashboard', 'ese.master')->name('dashboard');
    });
    
});





//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
