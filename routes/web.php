<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ProfileController;
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

Route::view('/','welcome')->name('home');


// Auth::routes();
Route::group(['middleware' => 'authenticatedmiddleware'], function(){

Route::view('/login','login')->name('loginpage');
Route::get('/register',[MainController::class, 'registerindex']);
Route::post('/logout', [MainController::class, 'logout'])->name('logout');
Route::view('/forget-password', 'forget-password')->name('forgetpasswordpage');
Route::post('/forget-password', [ForgetPasswordController::class, 'postEmail'])->name('forgetpassword');

//reset password routes 
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'getPassword']);
Route::post('/reset-password', [ResetPasswordController::class,'updatePassword'])->name('resetpassword');

Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::post('/register',[MainController::class, 'register'])->name('register');

});




/* This route group is where the authenticated users go to */
Route::group(['middleware' => ['auth', 'session.timeout']], function(){

    // Route group for admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        // all views
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/ajouterdesEtudiant', [AdminController::class , 'etudiant'])->name('addetudiant');
        Route::get('/listedesEtudiant', [AdminController::class , 'etudiantliste'])->name('listeetudiant');
        Route::view('/gestionfiliere', 'admin.pages.filiere')->name('filiere');
        Route::get('/gestionProfesseur', [AdminController::class, 'professeur'])->name('professeur');
        Route::view('/gestionAgentScolarite', 'admin.pages.agentscolarite')->name('agentscolarite');
        Route::view('/gestionAgentExamen', 'admin.pages.agentexamen')->name('agentexamen');
        Route::get('/gestionelement', [AdminController::class, 'element'])->name('element');
        Route::get('/gestionmodule', [AdminController::class, 'module'])->name('module');
        Route::get('/export', [AdminController::class, 'export'])->name('export');
        Route::get('/exportsample', [AdminController::class, 'exportsample'])->name('exportsample');
        Route::post('/import', [AdminController::class, 'import'])->name('import');
        Route::get('/logs', [AdminController::class, 'log'])->name('logspages');
        Route::get('/profile', [ProfileController::class, 'adminProfile'])->name('profilepage');

        // post 
        Route::post('/gestionfiliere', [AdminController::class, 'gestionfiliere'])->name('gestionfiliere');
        Route::post('/gestionmodule', [AdminController::class, 'gestionModule'])->name('gestionmodule');
        Route::post('/gestionelement', [AdminController::class, 'gestionElement'])->name('gestionelement');
        Route::post('/gestionProfesseur', [AdminController::class, 'gestionProfesseur'])->name('gestionprofesseur');
        Route::post('/gestionAgentScolarite', [AdminController::class, 'gestionAgentScolarite'])->name('gestionagentscolarite');
        Route::post('/gestionAgentExamen', [AdminController::class, 'gestionAgentExamen'])->name('gestionagentexamen');
        Route::post('/ajouterdesEtudiant', [AdminController::class , 'gestionetudiant'])->name('gestionetudiant');
        Route::post('/listedesEtudiant', [AdminController::class , 'gestionetudiantliste'])->name('gestionetudiantliste');
        Route::post('/logs', [AdminController::class, 'gestionlogs'])->name('logs');
        Route::post('/profile', [ProfileController::class, 'gestionAdminProfile'])->name('profile');
        Route::post('/dashboard', [AdminController::class, 'gestionDashboard'])->name('gestiondashboard');
    });
    // Route group for etudiant
    Route::group(['prefix' => 'etudiant', 'as' => 'etudiant.', 'middleware' => 'etudiant'], function(){
        Route::view('/dashboard', 'etudiant.master')->name('dashboard');
    });
    // Route group for Professeur
    Route::group(['prefix' => 'prof', 'as' => 'prof.', 'middleware' => 'professeur'], function(){
        Route::view('/dashboard', 'prof.master')->name('dashboard');
    });
    // Route group for service de scolarite
    Route::group(['prefix' => 'service_de_scolarite', 'as' => 'ess.', 'middleware' => 'agentscolarite'], function(){
        Route::view('/dashboard', 'ess.master')->name('dashboard');
    });
    // Route group for service d'examen
    Route::group(['prefix' => 'service_examen', 'as' => 'ese.', 'middleware' => 'agentexamen'], function(){
        Route::view('/dashboard', 'ese.master')->name('dashboard');
    });

    // redirecting authenticated user 
    Route::get('/routing',[MainController::class, 'routing'])->name('route');
    
});





//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
