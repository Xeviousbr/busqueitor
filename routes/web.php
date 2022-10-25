<?php

use Illuminate\Support\Facades\Route;

Route::resource('busqueitor', 'BusqueitorController');
Route::get('importatabelas', 'BusqueitorController@importa');