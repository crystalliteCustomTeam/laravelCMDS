<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainAPIController;



Route::post('/devices/validate/', [MainAPIController::class,"validate"]);
Route::get('/areas/{area_id}/live-feed', [MainAPIController::class,'livefeed']);
Route::post('/areas/{area_id}/alerts', [MainAPIController::class,'areaalerts']);
Route::get('/worksites/{worksite_id}/alerts', [MainAPIController::class,'worksitealerts']);
Route::get('/areas/{area_id}/device', [MainAPIController::class,'areadevice']);


Route::get('/worksite/{email}',[MainAPIController::class,'worksiteMobile']);
Route::get('/profile/{email}',[MainAPIController::class,'profileMobile']);
Route::get('/worksite/{id}/details',[MainAPIController::class,'worksiteMobiledetails']);
Route::get('/communication',[MainAPIController::class,'allcommunication']);
Route::get('/safety/guidelines/{email}',[MainAPIController::class,'safetyguideline']);
Route::get('/safety/guidelines/details/{id}',[MainAPIController::class,'safetyguidelineDetails']);
Route::get('/checkout/details/{email}',[MainAPIController::class,'checkoutMobile']);
Route::post('/login', [MainAPIController::class, 'login']);
Route::post('/logout', [MainAPIController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/media/{email}',[MainAPIController::class,'mediaMobile']);


