<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BlockController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/upload', function () {
    return view('upload');
});
Route::get('/course', [CourseController::class, 'index']);
Route::get('/lesson-by-course', [LessonController::class, 'getRelationWithCourse'])->name('getLessWCourse');
Route::post('/add-file', [BlockCOntroller::class, 'store'])->name('addFile');
