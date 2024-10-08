<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\DrawingFileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportFileController;
use App\Http\Controllers\SupplyController;
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
    return redirect('home');
});

Route::resource('home', HomeController::class)->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('drawings', DrawingController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('drawing-files', DrawingFileController::class);
    Route::resource('report-files', ReportFileController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('supplies', SupplyController::class);
});

require __DIR__.'/auth.php';
