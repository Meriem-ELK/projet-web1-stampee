<?php
use App\Routes\Route;
use App\Controllers\HomeController;


// Routes pour la page d'accueil
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/home/index', 'HomeController@index');


Route::dispatch();