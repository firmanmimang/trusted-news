<?php

use App\Http\Controllers\Backoffice\Access\PermissionController;
use App\Http\Controllers\Backoffice\Access\RoleController;
use App\Http\Controllers\Backoffice\Access\UserController;
use App\Http\Controllers\Backoffice\Auth\AuthenticateSessionController;
use App\Http\Controllers\Backoffice\Category\CategoryController;
use App\Http\Controllers\Backoffice\DashboardController;
use App\Http\Controllers\Backoffice\News\NewsController;
use App\Http\Controllers\Backoffice\Profile\ChangePasswordController;
use App\Http\Controllers\Backoffice\Profile\ProfileController;
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

      Route::group(['prefix' => 'profile', 'as'=> 'profile.'], function(){
        /**
          * cms change password user
          * route: CMS_PATH/change-password
          * name: cms.profile.password.edit
          * middleware: [auth:cms]
          */
        Route::get('/password', [ChangePasswordController::class, 'edit'])->name('password.edit')->middleware('role_or_permission:super admin|change password');
        /**
          * cms change password user handler
          * route: CMS_PATH/change-password
          * name: cms.profile.password.update
          * middleware: [auth:cms]
          */
        Route::put('/password', [ChangePasswordController::class, 'update'])->name('password.update')->middleware('role_or_permission:super admin|change password');

        /**
          * cms change profile user
          * route: CMS_PATH/change-profile
          * name: cms.profile.edit
          * middleware: [auth:cms]
          */
        Route::get('/', [ProfileController::class, 'edit'])->name('edit')->middleware('role_or_permission:super admin|edit profile');
        /**
          * cms change profile user handler
          * route: CMS_PATH/change-profile
          * name: cms.profile.update
          * middleware: [auth:cms]
          */
        Route::put('/', [ProfileController::class, 'update'])->name('update')->middleware('role_or_permission:super admin|edit profile');;
      });

      Route::group(['as'=> 'access.', 'prefix' => 'access',], function () {
        Route::group(['as'=> 'user.', 'prefix' => 'user', 'middleware' => ['role_or_permission:super admin|user management,cms',]], function(){
          /**
            * user index
            * route: CMS_PATH/access/user
            * name: cms.access.user.index
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::get('/', [UserController::class, 'index'])->name('index');
          /**
            * user index
            * route: CMS_PATH/access/user
            * name: cms.access.user.index
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::get('/stream', [UserController::class, 'stream'])->name('stream');
          /**
            * user index
            * route: CMS_PATH/access/user/create
            * name: cms.access.user.create
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::get('/create', [UserController::class, 'create'])->name('create');
          /**
            * user store
            * route: CMS_PATH/access/user
            * name: cms.access.user.store
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::post('/', [UserController::class, 'store'])->name('store');
          /**
            * user edit
            * route: CMS_PATH/access/user/edit/{user}
            * name: cms.access.user.edit
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
          /**
            * user update
            * route: CMS_PATH/access/user/edit/{user}
            * name: cms.access.user.update
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::put('/edit/{user}', [UserController::class, 'update'])->name('update');
          /**
            * user delete
            * route: CMS_PATH/access/user/{user}
            * name: cms.access.user.delete
            * middleware: [auth:cms, role_or_permission:super admin|user management]
            */
          Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
        });
  
        Route::group(['as'=> 'role.', 'prefix' => 'role', 'middleware' => ['role_or_permission:super admin|role management,cms',]], function(){
          /**
            * role index
            * route: CMS_PATH/access/role
            * name: cms.access.role.index
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
          Route::get('/', [RoleController::class, 'index'])->name('index');
          /**
            * role index
            * route: CMS_PATH/access/role/create
            * name: cms.access.role.create
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
          Route::get('/create', [RoleController::class, 'create'])->name('create');
          /**
            * role store
            * route: CMS_PATH/access/role
            * name: cms.access.role.store
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
          Route::post('/', [RoleController::class, 'store'])->name('store');
          /**
            * role edit
            * route: CMS_PATH/access/role/edit/{role}
            * name: cms.access.role.edit
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
            Route::get('/edit/{role}', [RoleController::class, 'edit'])->name('edit');
          /**
            * role update
            * route: CMS_PATH/access/role/edit/{role}
            * name: cms.access.role.update
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
          Route::put('/edit/{role}', [RoleController::class, 'update'])->name('update');
          /**
            * role delete
            * route: CMS_PATH/access/role/{role}
            * name: cms.access.role.delete
            * middleware: [auth:cms, role_or_permission:super admin|role management]
            */
          Route::delete('/{role}', [RoleController::class, 'destroy'])->name('delete');
        });
        
        /**
          * permission index
          * route: CMS_PATH/access/permission
          * name: cms.access.permission.index
          * middleware: [auth:cms, role_or_permission:super admin|permission management,cms]
          */
        Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index')->middleware(['role_or_permission:super admin|permission management,cms',]);
      });

      Route::group(['as'=> 'category.', 'prefix' => 'category', 'middleware' => ['role_or_permission:super admin|category management,cms',]], function(){
        /**
          * category index
          * route: CMS_PATH/category
          * name: cms.category.index
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        /**
          * category store
          * route: CMS_PATH/category
          * name: cms.category.create
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        /**
          * category store
          * route: CMS_PATH/category
          * name: cms.category.store
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        /**
          * category update
          * route: CMS_PATH/category/{category}
          * name: cms.category.edit
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        /**
          * category update
          * route: CMS_PATH/category/{category}
          * name: cms.category.update
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        /**
          * category delete
          * route: CMS_PATH/category/{category}
          * name: cms.category.delete
          * middleware: [auth:cms, role_or_permission:category management]
          */
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('delete');
      });

      Route::group(['as'=> 'news.', 'prefix' => 'news', 'middleware' => ['role_or_permission:super admin|post management,cms',]], function(){
        Route::group(['as'=> 'in-house.', 'prefix' => 'in-house'], function(){
          /**
            * news in-house index
            * route: CMS_PATH/news/in-house
            * name: cms.news.in-house.index
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/', [NewsController::class, 'index'])->name('index');
          /**
            * news in-house store
            * route: CMS_PATH/news/in-house/create
            * name: cms.news.in-house.create
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/create', [NewsController::class, 'create'])->name('create');
          /**
            * news in-house store
            * route: CMS_PATH/news/in-house/
            * name: cms.news.in-house.store
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::post('/', [NewsController::class, 'store'])->name('store');
          /**
            * news in-house update
            * route: CMS_PATH/news/in-house/{news}
            * name: cms.news.in-house.edit
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
          /**
            * news in-house update
            * route: CMS_PATH/news/in-house/{news}
            * name: cms.news in-house.update
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::put('/{news}', [NewsController::class, 'update'])->name('update');
          /**
            * news in-house delete
            * route: CMS_PATH/news/in-house/{news}
            * name: cms.news in-house.delete
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::delete('/{news}', [NewsController::class, 'destroy'])->name('delete');
        });
        Route::group(['as'=> 'third-party.', 'prefix' => 'third-party'], function(){
          /**
            * news third-party index
            * route: CMS_PATH/news/third-party
            * name: cms.news.third-party.index
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/', [NewsController::class, 'index'])->name('index');
          /**
            * news third-party store
            * route: CMS_PATH/news/third-party/create
            * name: cms.news.third-party.create
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/create', [NewsController::class, 'create'])->name('create');
          /**
            * news third-party store
            * route: CMS_PATH/news/third-party/
            * name: cms.news.third-party.store
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::post('/', [NewsController::class, 'store'])->name('store');
          /**
            * news third-party update
            * route: CMS_PATH/news/third-party/{news}
            * name: cms.news.third-party.edit
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
          /**
            * news third-party update
            * route: CMS_PATH/news/third-party/{news}
            * name: cms.news third-party.update
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::put('/{news}', [NewsController::class, 'update'])->name('update');
          /**
            * news third-party delete
            * route: CMS_PATH/news/third-party/{news}
            * name: cms.news third-party.delete
            * middleware: [auth:cms, role_or_permission:post management]
            */
          Route::delete('/{news}', [NewsController::class, 'destroy'])->name('delete');
        });
      });
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