<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/publication/works', [PublicationController::class, 'show']);