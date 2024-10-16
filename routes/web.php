<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [MainController::class, 'GetAllUser'])->name('users');
    Route::get('users/edituser/{id}', [MainController::class, 'EditUser']);
    Route::post('/upload', [MainController::class, 'upload'])->name('image.upload');
    Route::post('/uploadImage', [MainController::class, 'uploadimage'])->name('image.worksite.image');
    Route::post('/create/worksite', [MainController::class, 'createWorksite'])->name('create.worksite');
    Route::get('/worksite', [MainController::class, 'worksite'])->name('worksite');
    Route::post('/worksite/area', [MainController::class, 'area'])->name('worksite.area');
    Route::post('/worksite/area/user/assgin', [MainController::class, 'areaUserAssign'])->name('worksite.area.user');
    Route::get('/singleworksite/{id}/{area}', [MainController::class, 'workarea'])->name('worksite.area.detail');
    Route::get('/singleworksite/{id}', [MainController::class, 'singleworksite'])->name('worksite.singleworksite');
});

require __DIR__ . '/auth.php';
