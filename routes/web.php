<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('/ajax', [TeacherController::class, 'index']);
Route::get('/teacher/all', [TeacherController::class, 'allData']);
Route::post('/teacher/store/', [TeacherController::class, 'storeData']);
Route::get('/teacher/edit/{id}', [TeacherController::class, 'editData']);
Route::post('/teacher/update/{id}', [TeacherController::class, 'updateData']);
Route::get('/teacher/destroy/{id}', [TeacherController::class, 'destroyData']);
