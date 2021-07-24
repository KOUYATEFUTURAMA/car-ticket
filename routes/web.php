<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', 'SiteController@index');
Route::post('serche-depart', 'SiteController@sercheDepart')->name('serche-depart');

Route::get('/login-car-ticket/admin', function () {
   return view('auth.login');
});

Auth::routes();
Route::get('/confirmer_compte/{id}/{token}', 'Auth\RegisterController@confirmationCompte');
Route::post('/update_password', 'Auth\RegisterController@updatePassword')->name('update_password');
Route::get('/home', 'HomeController@index')->name('home');

//les routes du module Parametre 
Route::namespace('Parametre')->middleware('auth')->name('parametre.')->prefix('parametre')->group(function () {
    //Route resources
    Route::resource('localites', 'LocaliteController');
    Route::resource('marques', 'MarqueController');
    Route::resource('moteurs', 'MoteurController');
    Route::resource('puissances', 'PuissanceController');
    Route::resource('type-vehicules', 'TypeVehiculeController');
    
     //Route pour les listes dans boostrap table
    Route::get('liste-localites', 'LocaliteController@listeLocalite')->name('liste-localites');
    Route::get('liste-marques', 'MarqueController@listeMarque')->name('liste-marques');
    Route::get('liste-moteurs', 'MoteurController@listeMoteur')->name('liste-moteurs');
    Route::get('liste-puissances', 'PuissanceController@listePuissance')->name('liste-puissances');
    Route::get('liste-type-vehicules', 'TypeVehiculeController@listeTypeVehicule')->name('liste-type-vehicules');
});
//les routes du module Application 
Route::namespace('Application')->middleware('auth')->name('application.')->prefix('application')->group(function () {
    //Route resources
    Route::resource('compagnies', 'CompagnieController');
    Route::resource('vehicules', 'VehiculeController');
    Route::resource('chauffeurs', 'ChauffeurController');
    Route::resource('departs', 'DepartController');
    
    //Route pour les listes dans boostrap table
    Route::get('liste-compagnies', 'CompagnieController@listeCompagnie')->name('liste-compagnies');
    Route::get('liste-vehicules', 'VehiculeController@listeVehicule')->name('liste-vehicules');
    Route::get('liste-chauffeurs', 'ChauffeurController@listeChauffeur')->name('liste-chauffeurs');
    Route::get('liste-departs', 'DepartController@listeDepart')->name('liste-departs');
    
    //Route particulière
    Route::post('update-compagnie', 'CompagnieController@updateCompagnie')->name('update-compagnie');
    
    //Route paramétrée 
    Route::get('liste-chauffeurs-by-compagnie/{compagnie}', 'ChauffeurController@listeChauffeurByCompagnie');
    Route::get('liste-vehicules-by-compagnie/{compagnie}', 'VehiculeController@listeVehiculeByCompagnie');
    Route::get('liste-departs-by-date/{date}', 'DepartController@listeDepartByDate');
    Route::get('liste-departs-by-localites/{depart}/{arrivee}', 'DepartController@listeDepartByLocalite');
    Route::get('liste-departs-by-compagnie/{compagnie}', 'DepartController@listeDepartByCompagnie');
    Route::get('liste-departs-by-localites-date/{depart}/{arrivee}/{date}', 'DepartController@listeDepartByLocaliteDate');
    
    //** Etats **// 
    
    //Compagnies
    Route::get('liste-compagnies-pdf', 'CompagnieController@listeCompagniePdf');
    
    //Chauffeurs
    Route::get('liste-chauffeurs-pdf', 'ChauffeurController@listeChauffeurPdf');
    Route::get('liste-chauffeurs-by-compagnie-pdf/{compagnie}', 'ChauffeurController@listeChauffeurByCompagniePdf');
    
    //Vehicules
    Route::get('liste-vehicules-pdf', 'VehiculeController@listeVehiculePdf');
    Route::get('liste-vehicules-by-compagnie-pdf/{compagnie}', 'VehiculeController@listeVehiculeByCompagniePdf');
    
    //Départs 
    Route::get('liste-departs-pdf', 'DepartController@listeDepartPdf');
    Route::get('liste-departs-by-date-pdf/{date}', 'DepartController@listeDepartByDatePdf');
    Route::get('liste-departs-by-localites-pdf/{depart}/{arrivee}', 'DepartController@listeDepartByLocalitePdf');
    Route::get('liste-departs-by-localites-date-pdf/{depart}/{arrivee}/{date}', 'DepartController@listeDepartByLocaliteDatePdf');
    Route::get('liste-departs-by-compagnie-pdf/{compagnie}', 'DepartController@listeDepartByCompagniePdf');
});

//les routes du module Auth 
Route::namespace('Auth')->middleware('auth')->name('auth.')->prefix('auth')->group(function () {
    //Route resources
    Route::resource('users', 'UserController');
    
    //Route pour la vue des users compagnies
    Route::get('users-compagnies', 'UserController@userCompagnieVue')->name('users-compagnies');
    
    //Route pour les listes dans boostrap table
    Route::get('liste_users', 'UserController@listeUser')->name('liste_users');
    Route::get('liste-users-compagnie', 'UserController@listeUserCompagnie')->name('liste-users-compagnie');

    //Autres routes pour le profil
    Route::get('profil-informations', 'UserController@profil')->name('profil-informations');
    Route::get('infos-profil-to-update', 'UserController@infosProfiTolUpdate')->name('infos-profil-to-update');
    Route::put('update-profil/{id}', 'UserController@updateProfil');
    Route::get('update-password-page', 'UserController@updatePasswordPage');
    Route::post('update-password', 'UserController@updatePasswordProfil')->name('update-password');

    //Réinitialisation du mot de passe manuellement par l'administrateur 
    Route::delete('/reset_password_manualy/{user}', 'UserController@resetPasswordManualy');
});
