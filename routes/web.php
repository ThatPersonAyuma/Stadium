<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use App\Models\Rank;

// Route::get('/', function () {
//     $users = DB::table('users')->get();
//     return view('welcome', compact('users'));
// });

Route::get('/upload', function () {
    return view('upload');
});
Route::get('/view-courses', function () {
    // ambil semua data dari database
    $courses = App\Models\Course::with([
        'lessons.contents.cards.blocks'
    ])->get();

    // kirim ke view
    return view('view_courses', compact('courses'));
});

Route::get('/ranks', function () {
    $ranks = Rank::all();
    return view('ranks', compact('ranks'));
});

Route::get('/users', function () {
    $users = User::with('rank')->get();
    return view('users', compact('users'));
});
// Route::get('/view-courses', function () {
//     return view('view_course');
// });
Route::get('/course', [CourseController::class, 'index']);
Route::get('/lesson-by-course', [LessonController::class, 'getRelationWithCourse'])->name('getLessWCourse');
Route::post('/add-file', [BlockCOntroller::class, 'store'])->name('addFile');

use App\Http\Controllers\UserAvatarController;


Route::get('/user/avatar', [UserAvatarController::class, 'showForm'])->name('avatar.form');
Route::post('/user/avatar', [UserAvatarController::class, 'upload'])->name('avatar.upload');


// ini aku yang nambahin yh bang, tolong benerin kalo salh
Route::get('/', function () {
    return view('landing.index');
});
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'student'])
//         ->name('dashboard.index');

//     Route::get('/dashboard/profile', [DashboardController::class, 'profile'])
//         ->name('dashboard.profile');

//     Route::get('/dashboard/teacher', [DashboardController::class, 'teacher'])
//         ->name('dashboard.teacher');
// });

Route::get('/dashboard', [DashboardController::class, 'student'])
    ->name('dashboard.index');

Route::get('/dashboard/student', [DashboardController::class, 'student'])
    ->name('dashboard.student');
    
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');