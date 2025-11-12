<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Models\User;
use App\Models\Rank;

// Debug Session
Route::get('/', function () {
    $user = Auth::user();
    if ($user){
        return match ($user->role) {
            'student' => view('welcome.student', compact('user')),
            'teacher' => view('welcome.teacher', compact('user')),
            default   => abort(403),
        };
    }else{
        return view('loginpage');
    }
})->name('welcome');
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


Route::get('/login', function(){
    return view('loginpage');
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('checkLogin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

Route::middleware('auth')->group(function() { // dont fotget you must have route login
    Route::get('/debug-session', function () {
        return session()->all();
    });
});
// Resources
Route::resource('fasilitas', MatkulController::class);


//testing
Route::get('/test-websocket', 
    function (){
        return view('test_websocket');
    }
);
Route::post('/post-question',
    [QuizController::class, 'startQuestion']
);
Route::get('/post-question',
    function(){
        return view('post_question');
    }
);