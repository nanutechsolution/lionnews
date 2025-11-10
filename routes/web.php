<?php
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/search', [PublicController::class, 'search'])->name('search');

Route::get('/semua-kategori', [PublicController::class, 'allCategories'])
    ->name('categories.index.all');
// === ADMIN ===
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('articles', ArticleController::class);
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('tags', TagController::class)->except('show');
        Route::resource('users', UserController::class)
            ->except('show') // Kita tidak perlu halaman 'show'
            ->middleware('can:manage-users');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');
        Route::resource('pages', PageController::class)->except('show');
        Route::get('/media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::middleware('can:manage-users')->group(function () {
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        });
    });

Route::post('/articles/{article}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

// === PROFILE ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === PUBLIC ROUTES (taruh paling bawah) ===
Route::get('/author/{user}', [PublicController::class, 'authorShow'])->name('author.show');
Route::get('/tag/{tag:slug}', [PublicController::class, 'tagShow'])->name('tag.show');
require __DIR__ . '/auth.php';
Route::get('/category/{category:slug}', [PublicController::class, 'categoryShow'])->name('category.show');
Route::get('/read/{category:slug}/{article:slug}', [PublicController::class, 'articleShow'])->name('article.show');
Route::get('/{page:slug}', [PublicController::class, 'pageShow'])
    ->name('page.show');

