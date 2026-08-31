<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
//use App\Models\Product;

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

//Route::get('/', function () {
  //  return view('products.index');
//});
Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    //   $products = auth()->user()->is_admin
    //     ? Product::latest()->get()
    //     : collect();
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//admin block
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);
});
// Public — anyone can view, no login needed
Route::resource('products', ProductController::class)->only(['index', 'show']);



require __DIR__.'/auth.php';
