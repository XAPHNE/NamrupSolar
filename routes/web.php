<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\DrawingFileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportFileController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\UserController;
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

Route::resource('home', HomeController::class)->middleware(['auth', 'verified', 'must.change.password']);

Route::middleware('auth', 'must.change.password')->group(function () {
    Route::resource('profile', ProfileController::class);

    Route::resource('drawings', DrawingController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('drawing-files', DrawingFileController::class);
    Route::resource('report-files', ReportFileController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('supplies', SupplyController::class);

    Route::middleware('restrict.route.access')->group(function () {
        Route::resource('users', UserController::class);
    });

    Route::get('project-timeline', function () { return view('project-timeline'); });
});

require __DIR__.'/auth.php';
