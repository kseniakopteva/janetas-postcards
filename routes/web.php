<?php

use App\Http\Controllers\FolderController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Models\Folder;
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
    return view('home', [
        'folders' => Folder::where('parent_id', null)->orderBy('created_at', 'desc')->get()
    ]);
})->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

/* -------------------------------- Sections -------------------------------- */

Route::get('/{folder:slug}', [FolderController::class, 'show'])->name('folder.show');


Route::post('/folder/{isSection?}', [FolderController::class, 'store'])->name('folder.store');
Route::delete('/folder', [FolderController::class, 'destroy'])->name('folder.destroy');

// Route::get('/{folder:slug}/images/create', [ImageController::class, 'create'])->name('image.create');

Route::post('/images/store', [ImageController::class, 'store'])->name('image.store');

Route::get('/{folder:slug}/images/{image:slug}', [ImageController::class, 'show'])->name('image.show');

Route::delete('/image/destroy', [ImageController::class, 'destroy'])->name('image.destroy');
Route::delete('/images/destroy', [ImageController::class, 'mass_destroy'])->name('images.destroy');
Route::delete('/destroy', [ImageController::class, 'mass_destroy_images'])->name('images.mass.destroy');

Route::post('/image/update', [ImageController::class, 'update'])->name('image.update');

Route::get('/{folder?}', function ($folder = null) {
    if (!empty($folder)) {
        $folder_arr = explode('/', $folder);
        $slug = end($folder_arr);
        $folder = Folder::where('slug', $slug)->first();
    }
    return view('folders.show', ['folder' => $folder, 'ancestors' => $folder->ancestors()]);
})->where('folder', '.*');
