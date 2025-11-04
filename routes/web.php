<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->name('search');
Route::get('/{category:slug}', [PublicController::class, 'categoryShow'])
    ->name('category.show');
Route::get('/{category:slug}/{article:slug}', [PublicController::class, 'articleShow'])
    ->name('article.show');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('articles', ArticleController::class);

});
require __DIR__ . '/auth.php';
