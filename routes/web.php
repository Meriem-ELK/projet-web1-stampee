<?php
use App\Routes\Route;
use App\Controllers\HomeController;
use App\Controllers\TimbreController;


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
Route::get('/profil/edit', 'ProfilController@pageEdit');
Route::post('/profil/edit', 'ProfilController@edit');
Route::get('/profil/delete', 'ProfilController@delete');


// Routes Timbre
Route::get('/timbre', 'TimbreController@index');
Route::get('/timbre/show', 'TimbreController@show');
Route::get('/timbre/create', 'TimbreController@create');
Route::post('/timbre/store', 'TimbreController@store');
Route::get('/timbre/edit', 'TimbreController@edit');
Route::post('/timbre/edit', 'TimbreController@update');
Route::post('/timbre/delete', 'TimbreController@delete');



// Routes Enchere
Route::get('/enchere', 'EnchereController@index');
Route::get('/enchere/show', 'EnchereController@show');

Route::dispatch();