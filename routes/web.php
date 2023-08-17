<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GuestBookController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\LogoutController;
use App\Http\Controllers\Frontend\RegisterController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/backoffice/cms.php';

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/guest', GuestBookController::class)->name('guest');
Route::post('/guest', [GuestBookController::class, 'store'])->name('guest.store');

Route::get('/register', RegisterController::class)->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store')->middleware('guest');

Route::get('/login', LoginController::class)->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store')->middleware('guest');

Route::post('/login/{provider}/redirect', [LoginController::class, 'socialiteRedirect'])->name('login.socialite')->middleware('guest');
Route::get('/login/{provider}/callback', [LoginController::class, 'socialiteCallback'])->middleware('guest');
Route::post('/login/google/one-tap', [LoginController::class, 'googleOneTapLogin'])->name('login.google.one-tap')->middleware('guest');

Route::post('/logout', LogoutController::class)->name('logout')->middleware('auth');

// news comment create
Route::post('/com/{news:slug}', [HomeController::class, 'storeComment'])
      ->name('news.store.comment')
      ->middleware('auth');

// news comment edit
Route::put('/com/{news:slug}/{comment:slug}', [HomeController::class, 'updateComment'])
      ->name('news.edit.comment')
      ->middleware('auth');

// news comment delete
Route::delete('/com/{news:slug}/{comment:slug}', [HomeController::class, 'deleteComment'])
      ->name('news.delete.comment')
      ->middleware('auth');

// news show
Route::get('/{news}', [HomeController::class, 'show'])->name('news.show');

