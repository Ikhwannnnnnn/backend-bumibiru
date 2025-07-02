<?php

use App\Http\Controllers\C_Auth;
use App\Http\Controllers\C_Dashboard;
use App\Http\Controllers\C_Maps;
use App\Http\Controllers\C_Mentor;
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

Route::group([], function () {
    Route::get('/login', [C_Auth::class, 'index'])->name('login');
    Route::post('/login', [C_Auth::class, 'login'])->name('login');
    Route::get('/register', [C_Auth::class, 'register'])->name('register');
    Route::post('/register', [C_Auth::class, 'store'])->name('register');
    Route::get('/forgot-password', [C_Auth::class, 'forgotPass'])->name('forgot-password');
    Route::post('/forgot-password', [C_Auth::class, 'resetPass'])->name('forgot-password');
    // Route::get('/', [C_Maps::class, 'index'])->name('home');
    // Route::get('/search', [C_Maps::class, 'search'])->name('search');
});

Route::middleware('login')->group(function () {
    Route::get('/', [C_Dashboard::class, 'index'])->name('dashboard');
    Route::get('/logout', [C_Auth::class, 'logout'])->name('logout');
    Route::post('/saveMentor', [C_Mentor::class, 'store'])->name('saveMentor');
    Route::post('/resetPass/{email}', [C_Dashboard::class, 'resetPass'])->name('resetPass');
    Route::post('/updateEmail/{email}', [C_Dashboard::class, 'updateEmail'])->name('updateEmail');
});

Route::middleware('login', 'admin')->group(function () {
    Route::delete('/delete/{id}', [C_Dashboard::class, 'destroyMentor'])->name('destroyMentor');
    Route::get('/detail/{id}', [C_Dashboard::class, 'detailMentor'])->name('detailMentor');
});

use Illuminate\Support\Facades\Response;

Route::get('/mentor/file/{filename}', function ($filename) {
    $path = public_path('mentor/cv/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::download($path);
});



Route::get('/export-mentors', [C_Dashboard::class, 'exportMentors'])->name('exportMentors');
