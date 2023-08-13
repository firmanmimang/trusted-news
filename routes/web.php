<?php

use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GuestBookController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/backoffice/cms.php';

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');
Route::get('/contact', ContactController::class)->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/guest', GuestBookController::class)->name('guest');
Route::post('/guest', [GuestBookController::class, 'store'])->name('guest.store');
// news comment create
Route::post('/com/{news:slug}', [HomeController::class, 'storeComment'])->name('news.store.comment')->middleware('auth');
// news comment edit
Route::put('/com/{news:slug}/{comment:slug}', [HomeController::class, 'updateComment'])->name('news.edit.comment')->middleware('auth');
// news comment delete
Route::delete('/com/{news:slug}/{comment:slug}', [HomeController::class, 'deleteComment'])->name('news.delete.comment')->middleware('auth');
// news show
Route::get('/{news}', [HomeController::class, 'show'])->name('news.show');

