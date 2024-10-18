<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MainController;


Route::get('/', function () {
    return redirect('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [MainController::class, 'GetAllUser'])->name('users');
    Route::get('/users/delete/{id}', [MainController::class, 'deleteuser'])->name('user.delete');
    Route::get('users/edituser/{id}', [MainController::class, 'EditUser']);
    Route::post('/edituser', [MainController::class, 'EditUserPOST'])->name('user.edit');
    Route::post('/upload', [MainController::class, 'upload'])->name('image.upload');
    Route::post('/uploadImage', [MainController::class, 'uploadimage'])->name('image.worksite.image');
    Route::post('/create/worksite', [MainController::class, 'createWorksite'])->name('create.worksite');
    Route::get('/worksite', [MainController::class, 'worksite'])->name('worksite');
    Route::post('/worksite/area', [MainController::class, 'area'])->name('worksite.area');
    Route::post('/worksite/area/user/assgin', [MainController::class, 'areaUserAssign'])->name('worksite.area.user');
    Route::get('/singleworksite/{id}/{area}', [MainController::class, 'workarea'])->name('worksite.area.detail');
    Route::get('/singleworksite/{id}', [MainController::class, 'singleworksite'])->name('worksite.singleworksite');
    Route::get('/notifications', [MainController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/create', [MainController::class, 'notificationsCreate'])->name('notifications.create');
    Route::post('/notifications/worksite/send', [MainController::class, 'notificationsSend'])->name('notifications.send');
    Route::get('/guideline', [MainController::class, 'guide'])->name('guideline');
    Route::get('/media', [MainController::class, 'media'])->name('media');
    route::get('/media/delete/{id}',[MainController::class, 'mediaDelete'])->name('media.delete');
    Route::post('/upload-files', [MainController::class, 'Mediaupload'])->name('media.upload');
    Route::get('/guideline/checkpoint', [MainController::class, 'checkpoint'])->name('checkpoint');
    Route::post('/guideline/checkpoint/create', [MainController::class, 'checkpointCreate'])->name('checkpoint.create');
    Route::post('/guideline/create', [MainController::class, 'guideCreate'])->name('guideline.create');
});

require __DIR__ . '/auth.php';
