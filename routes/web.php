<?php
use App\Routes\Route;
use App\Controllers\HomeController;


// Routes pour la page d'accueil
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/home/index', 'HomeController@index');



// Routes pour les utilisateurs
Route::get('/user/create', 'UserController@create');
Route::post('/user/store', 'UserController@store');

// Routes d'authentification
Route::get('/login', 'AuthController@index');
Route::post('/login', 'AuthController@store');
Route::get('/logout', 'AuthController@delete');


// Routes Profil membre
Route::get('/profil', 'ProfilController@index');

Route::dispatch();