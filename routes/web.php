<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\YourTasksController;
use App\Http\Controllers\TestScraperController;
use App\Http\Controllers\AllTasksController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CatalogProductController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/test-scraper', [TestScraperController::class, 'test']);


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/all-tasks', [AllTasksController::class, 'index'])->name('all-tasks.index');
    Route::post('/all-tasks', [AllTasksController::class, 'store'])->name('all-tasks.store');
    Route::post('/all-tasks/{task}/assign', [AllTasksController::class, 'assign'])->name('all-tasks.assign');
    Route::delete('/all-tasks/{task}', [AllTasksController::class, 'destroy'])->name('all-tasks.destroy');

    Route::get('/your-tasks', [YourTasksController::class, 'index'])->name('your-tasks.index');
    Route::patch('/your-tasks/{task}', [YourTasksController::class, 'update'])->name('your-tasks.update');
    Route::delete('/your-tasks/{task}', [YourTasksController::class, 'destroy'])->name('your-tasks.destroy');


    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
    
    Route::get('/catalogs', [CatalogController::class, 'index'])->name('catalogs.index');
    Route::delete('/catalogs/{catalog}', [CatalogController::class, 'destroy'])->name('catalogs.destroy');
    Route::get('/catalogs/{catalog}', [CatalogProductController::class, 'show'])->name('catalogs.show');
    Route::post('/catalogs/{catalog}/products', [CatalogProductController::class, 'store'])->name('catalogs.products.store');
    Route::delete('/catalogs/{catalog}/products/{product}', [CatalogProductController::class, 'destroy'])->name('catalogs.products.destroy');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
