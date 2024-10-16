<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainAPIController;

Route::get('/getAllusers', [MainAPIController::class,"Getusers"]);

Route::post('/createUser', [MainAPIController::class,'CreateUser']);


