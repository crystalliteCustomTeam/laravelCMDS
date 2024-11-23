<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainAPIController;



Route::post('/devices/validate', [MainAPIController::class, "validateDevice"]);
Route::post('/areas/{area_id}/live-feed', [MainAPIController::class,'livefeed']); // Store Data coming from Device
Route::get('/areas_code/alerts', [MainAPIController::class,'getCodeWiseAlert']);
Route::post('/areas/{area_id}/alerts', [MainAPIController::class,'areaalerts']); //Store data coming from device.
Route::get('/worksites/{worksite_id}/alerts', [MainAPIController::class,'worksitealerts']);
Route::get('/areas/{area_id}/device', [MainAPIController::class,'areadevice']);
Route::post('/areas/{area_id}/accidents', [MainAPIController::class, 'storeAreaAccidents']);

Route::get('/worksite/{email}',[MainAPIController::class,'worksiteMobile']);
Route::get('/worksite/with/area/{email}',[MainAPIController::class,'worksiteMobilewitharea']);
Route::get('/profile/{email}',[MainAPIController::class,'profileMobile']);
Route::post('/profile/update',[MainAPIController::class,'profileupdate']);
Route::get('/worksite/{id}/details',[MainAPIController::class,'worksiteMobiledetails']);
Route::get('/communication',[MainAPIController::class,'allcommunication']);
Route::get('/communication/status',[MainAPIController::class,'readCommunication']);
Route::post('/communication/create/',[MainAPIController::class,'createcommunication']);
Route::get('/notifications/{email}',[MainAPIController::class,'alerts']);
Route::post('/password/forgot', [MainAPIController::class, 'forgotPassword']);
Route::post('/password/reset', [MainAPIController::class, 'resetPassword']);

Route::get('/settings/{email}',[MainAPIController::class,'settings']);
Route::post('/settings/update',[MainAPIController::class,'settingsUpdate']);

Route::post('/safety/views',[MainAPIController::class,'safetyview']);
Route::get('/safety/views/{id}',[MainAPIController::class,'safetyuserCount']);

Route::get('/safety/guidelines/{email}',[MainAPIController::class,'safetyguideline']);
Route::get('/safety/guidelines/details/{id}',[MainAPIController::class,'safetyguidelineDetails']);
Route::get('/checkout/details/{email}',[MainAPIController::class,'checkoutMobile']);
Route::post('/sendnotification',[MainAPIController::class,'writeUserData']);
Route::post('/login', [MainAPIController::class, 'login']);
Route::post('/logout', [MainAPIController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/user/delete', [MainAPIController::class, 'deleteuser']);

Route::get('/media/{email}',[MainAPIController::class,'mediaMobile']);


