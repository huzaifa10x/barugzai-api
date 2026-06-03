<?php

use App\Http\Controllers\MetaDataController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/meta-data/create', [MetaDataController::class, 'create']);
    Route::post('/meta-data/store', [MetaDataController::class, 'store']);
    Route::get('/meta-data/edit/{id}', [MetaDataController::class, 'edit'])->name('meta-data.edit');
    Route::put('/meta-data/update/{id}', [MetaDataController::class, 'update'])->name('meta-data.update');
    Route::post('/meta-data/delete/{id}', [MetaDataController::class, 'destroy']);
    Route::get('/meta-data', [MetaDataController::class, 'index'])->name('meta-data');

    Route::get('/contact/create', [ContactController::class, 'create']);
    Route::post('/contact/store', [ContactController::class, 'store']);
    Route::get('/contact/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
    Route::put('/contact/update/{id}', [ContactController::class, 'update'])->name('contact.update');
    Route::post('/contact/delete/{id}', [ContactController::class, 'destroy'])->name('contact.delete');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
});

require __DIR__ . '/auth.php';
