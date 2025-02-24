<?php

use App\Http\Controllers\EventStreamController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GuestBookController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\LogoutController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\Route;

// Route::domain('{lang}.tb-smartweb-v3.io')->group(function () {
      // Route::group(['middleware' => [Localization::class]], function () {
            // Route::get('/', HomeController::class)->name('home');
            // Route::get('/about', AboutController::class)->name('about');
      // });
// });

require __DIR__.'/backoffice/cms.php';

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/guest', GuestBookController::class)->name('guest');
Route::post('/guest', [GuestBookController::class, 'store'])->name('guest.store');

// register frontend
Route::get('/register', RegisterController::class)
      ->name('register')
      ->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])
      ->name('register.store')
      ->middleware('guest');

// login frontend
Route::get('/login', LoginController::class)->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])
      ->name('login.store')
      ->middleware('guest');

// login socialite
Route::post('/login/{provider}/redirect', [LoginController::class, 'socialiteRedirect'])
      ->name('login.socialite')
      ->middleware('guest');
Route::get('/login/{provider}/callback', [LoginController::class, 'socialiteCallback'])
      ->middleware('guest');
Route::post('/login/google/one-tap', [LoginController::class, 'googleOneTapLogin'])
      ->name('login.google.one-tap')
      ->middleware('guest');

// logout frontend
Route::post('/logout', LogoutController::class)->name('logout')
      ->middleware('auth');

// profile user
Route::get('/profile', ProfileController::class)->name('profile')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware(['auth', 'can:edit profile']);
Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password')->middleware(['auth', 'can:edit profile']);
Route::put('/profile/ses/t/{token:payload}', [ProfileController::class, 'sessionTerminate'])->name('profile.session.terminate')->middleware('auth');

// coba sse
Route::get('stream/view', function () {
      return view('welcome');
});
Route::get('stream', [EventStreamController::class, 'stream']);

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
Route::get('/{news}', [HomeController::class, 'show'])
      ->name('news.show');

