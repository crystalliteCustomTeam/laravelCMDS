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
    Route::get('/worksite/delete/{id}', [MainController::class, 'worksiteDelete'])->name('worksite.delete');
    Route::get('/worksite/detail/{id}', [MainController::class, 'worksiteDetail'])->name('worksite.detail');
    Route::post('/worksite/area', [MainController::class, 'area'])->name('worksite.area');
    Route::post('/worksite/area/user/assgin', [MainController::class, 'areaUserAssign'])->name('worksite.area.user');
    Route::get('/worksite/area/user/remove/{id}', [MainController::class, 'areaUserRemove'])->name('worksite.area.remove');
    Route::get('/singleworksite/{id}/{area}', [MainController::class, 'workarea'])->name('worksite.area.detail');
    Route::get('/singleworksite/{id}/delete/{area}', [MainController::class, 'workareaDelete'])->name('worksite.area.delete');
    Route::get('/singleworksite/{id}', [MainController::class, 'singleworksite'])->name('worksite.singleworksite');
    Route::get('/notifications', [MainController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/delete/{id}', [MainController::class, 'notificationsDelete'])->name('notifications.delete');
    Route::post('/notifications/create', [MainController::class, 'notificationsCreate'])->name('notifications.create');
    Route::post('/notifications/worksite/send', [MainController::class, 'notificationsSend'])->name('notifications.send');
    Route::get('/guideline', [MainController::class, 'guide'])->name('guideline');
    Route::post('/worksite/area/edit', [MainController::class, 'areaEdit'])->name('worksite.area.edit');
    Route::get('/guideline/delete/{id}', [MainController::class, 'guideDelete'])->name('guideline.delete');
    Route::get('/media', [MainController::class, 'media'])->name('media');
    route::get('/media/delete/{id}',[MainController::class, 'mediaDelete'])->name('media.delete');
    Route::post('/upload-files', [MainController::class, 'Mediaupload'])->name('media.upload');
    Route::get('/guideline/checkpoint', [MainController::class, 'checkpoint'])->name('checkpoint');
    Route::post('/guideline/checkpoint/create', [MainController::class, 'checkpointCreate'])->name('checkpoint.create');
    Route::get('/guideline/checkpoint/edit/{id}', [MainController::class, 'checkpointEdit'])->name('checkpoint.edit');
    Route::post('/guideline/checkpoint/edit/', [MainController::class, 'checkpointEditPOST'])->name('checkpoint.edit.post');
    Route::post('/guideline/create', [MainController::class, 'guideCreate'])->name('guideline.create');
    Route::get('/guideline/checkpoint/delete/{id}', [MainController::class, 'checkpointDelete'])->name('checkpoint.delete');
});


require __DIR__ . '/auth.php';
