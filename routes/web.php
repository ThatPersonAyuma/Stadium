<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ManajemenTeachersController;
use App\Http\Controllers\ManajemenCourseController;
use App\Http\Controllers\ManajemenQuizController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserAvatarController;
use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', fn () => view('auth.choose-role'))->name('register');
Route::get('/register/student', fn () => view('auth.register'))->name('register.student');
Route::get('/register/teacher', fn () => view('auth.register-teacher'))->name('register.teacher');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/dashboard', [DashboardController::class, 'student'])
    ->name('dashboard.index');

Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/admin/manajemen-teacher', [ManajemenTeachersController::class, 'index'])
        ->name('manajemen.teachers');
    // 1. Daftar (Index)
    Route::get('/manajemen-courses', [ManajemenCourseController::class, 'index'])
    ->name('manajemen-course.index');
    
    // 2. Detail (Show)
    Route::get('/manajemen-courses/{course}', [ManajemenCourseController::class, 'show'])
    ->name('manajemen-course.show');

    Route::post('/manajemen-courses/action', [ManajemenCourseController::class, 'action'])
        ->name('manajemen.course.action');

    Route::get('/manajemen-courses/content/{content}', [ManajemenCourseController::class, 'previewContent'])
        ->name('manajemen-course.preview');

    Route::post('/admin/manajemen-teacher/action', [ManajemenTeachersController::class, 'action'])
        ->name('manajemen.teachers.action');


    Route::get('/manajemen-quiz', [ManajemenQuizController::class, 'index'])->name('manajemen-quiz.index');
    Route::post('/manajemen-quiz/action', [ManajemenQuizController::class, 'action'])->name('manajemen.quiz.action');
    // 2. Detail / Review Quiz
    Route::get('/manajemen-quiz/{id}', [ManajemenQuizController::class, 'show'])->name('manajemen-quiz.show');
});


Route::middleware('auth')->group(function () { // dont fotget you must have route login
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])
        ->name('leaderboard.index');
    Route::get('/debug-session', function () {
        return session()->all();
    });
    Route::get('/quiz', [QuizController::class, 'ShowIndex'])->name('quiz.index');
    Route::get('/course', [CourseController::class, 'index'])->name('course.index');
    Route::get('/profile', [AuthController::class, 'ProfileIndex'])->name('profile.index');
    Route::put('/profile/update',
        [AuthController::class, 'UpdateProfile']
    )->name('profile.update');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard.index');
});
Route::middleware(['auth', 'role:teacher'])->group(function () {
    // Route::get('/teacher/dashboard', [DashboardController::class, 'teacher'])
    //     ->name('dashboard.teacher');
    /* #region resource*/
    Route::resource('courses', CourseController::class)->except(['index', 'show']);
    Route::resource('lessons', LessonController::class)->except(['index', 'show']);
    Route::resource('contents', ContentController::class)->except(['index', 'show']);
    Route::resource('cards', CardController::class)->except(['index', 'show']);
    Route::resource('blocks', BlockController::class)->except(['index', 'show']);
    /* #endregion */
    Route::prefix('teacher/courses')->name('teacher.courses.')->group(function () {
        // Route::get('/', [CourseController::class, 'teacherIndex'])->name('index');
        Route::get('/create', [CourseController::class, 'teacherCreate'])->name('create');
        Route::get('/{course}/edit', [CourseController::class, 'teacherEdit'])->name('edit');
        Route::get('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonShow'])->name('lessons.show');
        Route::post('/{course}/lessons', [CourseController::class, 'teacherLessonStore'])->name('lessons.store');
        Route::patch('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonUpdate'])->name('lessons.update');
        Route::delete('/{course}/lessons/{lesson}', [CourseController::class, 'teacherLessonDestroy'])->name('lessons.destroy');
        Route::delete('/{course}', [CourseController::class, 'teacherDestroy'])->name('destroy');
        Route::get('/{course}', [CourseController::class, 'teacherShow'])->name('show');
    });
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/quiz', [QuizController::class, 'index'])->name('index');
        Route::get('/quiz/create', [QuizController::class, 'create'])->name('create');
        Route::put('/quiz/update/{quiz}', [QuizController::class, 'update'])->name('update');
        Route::post('/quiz/store', [QuizController::class, 'store'])->name('store');
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
/* #region resource*/
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
Route::get('/contents', [ContentController::class, 'index'])->name('contents.index');
Route::get('/contents/{content}', [ContentController::class, 'show'])->name('contents.show');
Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');
Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
Route::get('/blocks/{block}', [BlockController::class, 'show'])->name('blocks.show');
/* #endregion */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/course/{course}', [CourseController::class, 'detail'])->name('course.detail');
    Route::get('/course/{course}/lesson/{lesson}/content/{content}', [LessonController::class, 'play'])->name('lesson.show');
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/test', fn () => 'OK STUDENT');
        Route::post('/register', [QuizController::class, 'studentJoin'])->name('post-register');
        Route::post('/post-answer', [QuizController::class, 'HandleAnswer'])->name('answer');
        Route::post('/get-scoreboard', [QuizController::class, 'GetScoreboard'])->name('get-scoreboard');
    });
    Route::post('/lesson/answer', [BlockController::class, 'check_answer'])->name('lesson-answer');
    Route::get('/leaderboard/fetch', [LeaderboardController::class, 'fetch']);
});

