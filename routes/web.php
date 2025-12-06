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
use App\Http\Controllers\UserAvatarController;

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

Route::get('/lesson-by-course', [LessonController::class, 'getRelationWithCourse'])->name('getLessWCourse');
Route::post('/content/finish', [BlockController::class, 'finish_content'])->name('finish-content');
Route::post('/add-file', [BlockController::class, 'store'])->name('addFile');





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







Route::resource('courses', CourseController::class);
// Route::resource('quiz', QuizController::class);

    
Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

Route::middleware('auth')->group(function() { // dont fotget you must have route login
    Route::get('/debug-session', function () {
        return session()->all();
    });
    Route::get('/quiz', [QuizController::class, 'ShowIndex'])->name('quiz.index');
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
    Route::prefix('quiz')->name('quiz.')->group(function (){
        Route::get('/quiz', [QuizController::class, 'index'])->name('index');
        Route::get('/quiz/create', [QuizController::class, 'create'])->name('create');
        Route::put('/quiz/update/{quiz}', [QuizController::class, 'update'])->name('update');
        Route::delete('/{quiz}/delete', [QuizController::class, 'delete'])->name('delete');
        Route::get('/monitoring/{quiz}', [QuizController::class, 'TeacherMonitoring'])->name('monitoring');
        Route::post('/open-quiz/{quiz}', [QuizController::class, 'OpenQuiz'])->name('open');
        Route::post('/start', [QuizController::class, 'startQuestion'])->name('start');
        Route::post('/send-question', [QuizController::class, 'sendQuestion'])->name('send');
        Route::post('/end-question', [QuizController::class, 'EndQuestion'])->name('end-question');
        Route::post('/post-scoreboard', [QuizController::class, 'BroadcastScoreboard'])->name('scoreboard');
        Route::post('/end-quiz', [QuizController::class, 'EndQuiz'])->name('end-quiz');
        Route::get('/{quiz}/manage', [QuizController::class, 'manage'])->name('manage');
        Route::get('/{quiz}/question/create', [QuizController::class, 'CreateQuestion'])->name('question.create');
        Route::get('/{quiz}/question/edit/{question}', [QuizController::class, 'EditQuestion'])->name('question.edit');
        Route::delete('/{quiz}/question/delete/{question}', [QuizController::class, 'DeleteQuestion'])->name('question.delete');
        Route::post('/{quiz}/question/store', [QuizController::class, 'StoreQuestion'])->name('question.store');
        Route::put('/question/update/{question}', [QuizController::class, 'UpdateQuestion'])->name('question.update');
    }); 
    
});

Route::middleware(['auth', 'role:student'])->group(function() { 
    Route::get('/course', [CourseController::class, 'index'])->name('course.index');
    Route::get('/course/{course}', [CourseController::class, 'detail'])->name('course.detail');
    Route::get('/course/{course}/lesson/{lesson}/content/{content}', [LessonController::class, 'play'])->name('lesson.show');
    // Route::get('/student/dashboard', [DashboardController::class, 'student'])
    //     ->name('dashboard.student');
    Route::get('/dashboard', [DashboardController::class, 'student'])
        ->name('dashboard.index');
    Route::prefix('quiz')->name('quiz.')->group(function (){
        Route::get('/test', fn() => 'OK STUDENT');
        // Route::get('/register', [QuizController::class, 'ShowRegister'])->name('running');
        Route::post('/register', [QuizController::class, 'studentJoin'])->name('post-register');
        Route::post('/post-answer', [QuizController::class, 'HandleAnswer'])->name('answer');
        Route::get('/running-quiz/{quiz_id}', function($quiz_id){
            return view('quiz.quiz_running',['quiz_id' => $quiz_id]);
        } )->name('play');
    }); 
    Route::post('/lesson/answer', [BlockController::class, 'check_answer'])->name('lesson-answer');
});


// Quiz CRUD



// Manajemen pertanyaan

// Testing Error Pages
// Route::get('/test-403', fn() => abort(403));
// Route::get('/test-500', fn() => abort(500));
// Route::get('/test-404', fn() => abort(404));
