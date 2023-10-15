<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\documController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Auth;
use DateTime;


Route::group(['middleware'=>'XSS'], function() {

    Route::get('/', [loginController::class, 'index'])->name('index');
    
    Route::get('/php', function () {
        dd(phpinfo());
    });
    
    Route::get('/time', function () {
        $date = new DateTime('1696482000');
        return $date->format('Y-m-d h:i');
    });
    
    Route::get('/user-registe', [loginController::class, 'register'])->name('register');
    
    Route::post('/user-registe', [loginController::class, 'registerUser']);
    
    Route::get('/login', [loginController::class, 'log'])->name('login');
    
    Route::post('/login', [loginController::class, 'login']);
    
    Route::post('/logout', [loginController::class, 'logout']);
    
    //USER
    
    Route::get('/user', [userController::class, 'index'])->middleware('auth')->name('user');
    
    Route::get('/user-create', [userController::class, 'create'])->middleware('auth');
    
    Route::post('/user-create', [userController::class, 'store'])->middleware('auth');
    
    Route::get('/user-codes', [userController::class, 'code'])->middleware('auth')->name('code');
    
    Route::get('/user-code', [userController::class, 'newcode'])->middleware('auth');
    
    Route::post('/user-code', [userController::class, 'createcode'])->middleware('auth');
    
    Route::get('/user-pass/{id}', [userController::class, 'pass'])->middleware('auth');
    
    Route::post('/user-pass/{id}', [userController::class, 'newpass'])->middleware('auth');
    
    Route::get('/user-recovery', [userController::class, 'recoverypass']);
    
    Route::post('/user-recovery', [userController::class, 'sendrecoverypass']);
    
    Route::get('/user-passR/{code}', [userController::class, 'resetpassView']);
    
    Route::post('/user-passR/{id}', [userController::class, 'resertpass']);
    
    Route::post('/user-delete/{id}', [userController::class, 'destroy'])->middleware('auth');
    
    Route::get('/user-guest', [userController::class, 'guest'])->middleware('auth')->name('guest');
    
    Route::post('/user-guest/{location?}', [userController::class, 'storeGuest'])->middleware('auth');
    
    Route::post('/user-guestp/{id}', [userController::class, 'guestp'])->middleware('auth');
    
    //DOC
    
    Route::get('/docum/{location?}', [documController::class, 'index'])->middleware('auth')->name('docum');
    
    Route::get('/docum-shared/{location?}', [documController::class, 'shared'])->middleware('auth')->name('shared');
    
    Route::get('/docum-guests/{location?}', [documController::class, 'guests'])->middleware('auth');
    
    Route::get('/docum-guestsback/{location?}', [documController::class, 'guestsback'])->middleware('auth');
    
    Route::get('/docum-invite/{location?}', [documController::class, 'invite'])->middleware('auth');
    
    Route::post('/docum-invite', [documController::class, 'inviteUser'])->middleware('auth');
    
    Route::post('/docum-uninvite/{id}', [documController::class, 'uninviteUser'])->middleware('auth');
    
    Route::get('/docum-bin/{location?}', [documController::class, 'papelera'])->middleware('auth')->name('bin');
    
    Route::get('/docum-graveyard/{location?}', [documController::class, 'graveyard'])->middleware('auth')->name('graveyard');
    
    Route::get('/docum-view/{id}', [documController::class, 'view'])->middleware('auth');
    
    Route::post('/docum-download/{id}', [documController::class, 'download'])->middleware('auth');
    
    Route::post('/docum-move/{id}', [documController::class, 'move'])->middleware('auth');
    
    Route::get('/docum-upload/{location?}', [documController::class, 'create'])->middleware('auth');
    
    Route::post('/docum-upload/{location?}', [documController::class, 'store'])->middleware('auth');
    
    Route::post('/docum-bined/{id}', [documController::class, 'bin'])->middleware('auth');
    
    Route::post('/docum-grave/{id}', [documController::class, 'grave'])->middleware('auth');
    
    Route::post('/docum-delete/{id}', [documController::class, 'destroy'])->middleware('auth');
    
    Route::post('/docum-restore/{id}', [documController::class, 'restore'])->middleware('auth');
    
    //DOCS GROUP
    
    Route::post('/docum-moves/', [documController::class, 'moves'])->middleware('auth');
    
    Route::post('/docum-bineds/', [documController::class, 'bins'])->middleware('auth');
    
    Route::post('/docum-graves/', [documController::class, 'graves'])->middleware('auth');
    
    Route::post('/docum-deletes/', [documController::class, 'destroys'])->middleware('auth');
    
    Route::post('/docum-restores/', [documController::class, 'restores'])->middleware('auth');
    
    //FOLDER
    
    Route::get('/folder-back/{location?}', [documController::class, 'backFolder'])->middleware('auth');
    
    Route::get('/folder-sharedback/{location?}', [documController::class, 'backFolderShared'])->middleware('auth');
    
    Route::get('/folder-create/{location?}', [documController::class, 'createFolder'])->middleware('auth');
    
    Route::post('/folder-create/{location?}', [documController::class, 'storeFolder'])->middleware('auth');

});