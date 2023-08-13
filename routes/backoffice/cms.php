<?php

use App\Http\Controllers\Backoffice\Auth\AuthenticateSessionController;
use App\Http\Controllers\Backoffice\DashboardController;
use Illuminate\Support\Facades\Route;

if(config("cms.enable") && config("cms.path")){
  Route::group(['prefix' => config("cms.path"), 'as' => 'cms.'], function(){
    // dashboard cms
    Route::middleware(['auth:cms'])->group(function(){
      /**
       * cms logout
       * route: CMS_PATH/logout
       * name: cms.logout.process
       * middleware: [auth:cms]
       */
      Route::post('/logout', [AuthenticateSessionController::class, 'destroy'])->name('logout');
      /**
        * cms dashboard
        * route: CMS_PATH/dashboard
        * name: cms.dashboard
        * middleware: [auth:cms]
        */
      Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // auth cms
    Route::middleware(['guest:cms'])->group(function(){
      /**
       * cms login
       * route: CMS_PATH/login
       * name: cms.login
       * middleware: [RedirectIfAuthenticatedCms]
       */
      Route::get('/login', [AuthenticateSessionController::class, 'index'])->name('login');
      /**
       * cms login
       * route: CMS_PATH/login
       * name: cms.login
       * middleware: [RedirectIfAuthenticatedCms]
       */
      Route::post('/login', [AuthenticateSessionController::class, 'store'])->name('login');
    });
  });
}