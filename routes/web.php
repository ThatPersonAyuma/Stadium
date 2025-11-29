<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Models\User;
use App\Models\Content;
use App\Models\Rank;

// Debug Session
Route::get('/welcome', function () {
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
// Route::get('/', function (){return view('welcome1');})->name('welcome');
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


// Route::get('/login', function(){
//     return view('loginpage');
// })->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('checkLogin');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/users', function () {
    $users = User::with('rank')->get();
    return view('users', compact('users'));
});
// Route::get('/view-courses', function () {
//     return view('view_course');
// });
Route::get('/course', [CourseController::class, 'index'])->name('course.index');
Route::get('/course/{course}', [CourseController::class, 'detail'])->name('course.detail');
Route::get('/course/{course}/lesson/{lesson}/content/{content}', [LessonController::class, 'play'])->name('lesson.show');
Route::get('/lesson-by-course', [LessonController::class, 'getRelationWithCourse'])->name('getLessWCourse');
Route::post('/add-file', [BlockController::class, 'store'])->name('addFile');



use App\Http\Controllers\UserAvatarController;


Route::get('/user/avatar', [UserAvatarController::class, 'showForm'])->name('avatar.form');
Route::post('/user/avatar', [UserAvatarController::class, 'upload'])->name('avatar.upload');


// ini aku yang nambahin yh bang, tolong benerin kalo salh
Route::get('/', function () {
    return view('landing.index');
});
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', fn() => view('auth.choose-role'))->name('register');
Route::get('/register/student', fn() => view('auth.register'))->name('register.student');
Route::get('/register/teacher', fn() => view('auth.register-teacher'))->name('register.teacher');
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

Route::get('/student/dashboard', [DashboardController::class, 'student'])
    ->name('dashboard.student');


    
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function() { // dont fotget you must have route login
    Route::get('/debug-session', function () {
        return session()->all();
    });
});
Route::middleware(['auth', 'role:teacher'])->group(function() { 
    Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])
        ->name('dashboard.teacher');
    Route::prefix('teacher/courses')->name('teacher.courses.')->group(function () {
        Route::get('/', [CourseController::class, 'teacherIndex'])->name('index');
        Route::get('/create', [CourseController::class, 'teacherCreate'])->name('create');
        Route::get('/{course}/edit', [CourseController::class, 'teacherEdit'])->name('edit');
        Route::get('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonShow'])->name('lessons.show');
        Route::post('/{course}/lessons', [CourseController::class, 'teacherLessonStore'])->name('lessons.store');
        Route::patch('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonUpdate'])->name('lessons.update');
        Route::delete('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonDestroy'])->name('lessons.destroy');
        Route::delete('/{course}', [CourseController::class, 'teacherDestroy'])->name('destroy');
        Route::get('/{course}', [CourseController::class, 'teacherShow'])->name('show');
    });
});
// Resources
// Route::resource('fasilitas', MatkulController::class);
// Route::resource('contents', ContentController::class);
Route::Resource('contents', ContentController::class);
// To get all of courses that available use this get
Route::Resource('courses', CourseController::class);
Route::Resource('blocks', BlockController::class);
Route::Resource('lessons', LessonController::class);
Route::Resource('cards', CardController::class);
Route::get('get-type', [BlockController::class, 'getType'])->name('get-type');
// Use this to get the data of all of the lessons in a course
Route::get('get-lessons/{course}', [CourseController::class, 'getAllLessonOFACourse'])->name('get-lessons');
// Use this to get the blocks needed for building a card
Route::get('/get-blocks/{card}', // This is for getting all of blocks data of a card
    [CardController::class, 'getBlocksOfCard']
)->name('card.get-blocks');
Route::get('get-progress/{course}/{student}', [CourseController::class, 'getStudentCourseProgress'])->name('get-progress');
Route::get('/get-cards/{content}', // This is for getting all of card datas of a content
    [ContentController::class, 'getCards']
)->name('card.get-cards');


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

Route::get('/edit-content',
    function(){
        $content = Content::findOrFail(1);
        return view('TESTING.change_content', compact('content'));
    }
);

Route::get('/delete-block',
    function(){
        return view('TESTING.test_delete');
    }
);
// Route::get('/get-a-card/{content}',
//     // function(){
//         [ContentController::class, 'getCards']
//         // $cards = ContentController::getCards(Content::findOrFail(1));
//         // $blocks = Content::findOrFail(1)->load('cards.blocks');
//         // return view('TESTING.card', compact('cards', 'blocks'));
//     // }
// );


// Testing Error Pages
// Route::get('/test-403', fn() => abort(403));
// Route::get('/test-500', fn() => abort(500));
// Route::get('/test-404', fn() => abort(404));
