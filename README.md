After cloning run this: <br>
1. cp .env.example .env <br>
2. composer install <br>
3. php artisan key:generate <br>
4. npm install <br>
5. npm run build <br>
Don't forget to change the database setting in .env file <br>
After that run php artisan migrte <br>
<br>
*Note if yougot error on storage use php artisan storage:link<br>
as it will create a symbolic link (symlink) from storage/app/public to public/storage

*Note:*  
Font Awesome is included locally via npm.  
Please make sure to run: 
npm install @fortawesome/fontawesome-free



## Project Structure

```text
Stadium/
|-- app/
|   |-- Enums/
|   |   |-- AccountStatus.php
|   |   |-- ContentType.php
|   |   |-- CourseStatus.php
|   |   |-- QuizChoice.php
|   |   |-- SocialMediaType.php
|   |   `-- UserRole.php
|   |-- Events/
|   |   |-- AnswerSubmitted.php
|   |   |-- ParticipantRegistered.php
|   |   |-- QuestionSent.php
|   |   |-- QuizEnd.php
|   |   |-- ScoreBoard.php
|   |   `-- SendAnswerAndScore.php
|   |-- Helpers/
|   |   |-- FileHelper.php
|   |   `-- Utils.php
|   |-- Http/
|   |   |-- Controllers/
|   |   |   |-- AuthController.php
|   |   |   |-- BlockController.php
|   |   |   |-- CardController.php
|   |   |   |-- ContentController.php
|   |   |   |-- Controller.php
|   |   |   |-- CourseController.php
|   |   |   |-- DashboardController.php
|   |   |   |-- LeaderboardController.php
|   |   |   |-- LessonController.php
|   |   |   |-- ManajemenCourseController.php
|   |   |   |-- ManajemenQuizController.php
|   |   |   |-- ManajemenTeachersController.php
|   |   |   |-- QuizController.php
|   |   |   |-- StudentController.php
|   |   |   `-- UserAvatarController.php
|   |   `-- Middleware/
|   |       |-- AuthUser.php
|   |       |-- CourseAuth.php
|   |       `-- RoleMiddleware.php
|   |-- Models/
|   |   |-- Admin.php
|   |   |-- Block.php
|   |   |-- Card.php
|   |   |-- Content.php
|   |   |-- Course.php
|   |   |-- Lesson.php
|   |   |-- Quiz.php
|   |   |-- QuizParticipant.php
|   |   |-- QuizQuestion.php
|   |   |-- QuizQuestionChoice.php
|   |   |-- Rank.php
|   |   |-- Student.php
|   |   |-- StudentContentProgress.php
|   |   |-- Teacher.php
|   |   `-- User.php
|   `-- Providers/
|       `-- AppServiceProvider.php
|-- bootstrap/
|   |-- cache/
|   |   `-- .gitignore
|   |-- app.php
|   `-- providers.php
|-- config/
|   |-- app.php
|   |-- auth.php
|   |-- broadcasting.php
|   |-- cache.php
|   |-- database.php
|   |-- filesystems.php
|   |-- logging.php
|   |-- mail.php
|   |-- queue.php
|   |-- reverb.php
|   |-- services.php
|   `-- session.php
|-- database/
|   |-- factories/
|   |   |-- StudentFactory.php
|   |   |-- TeacherFactory.php
|   |   `-- UserFactory.php
|   |-- migrations/
|   |   |-- 0001_01_01_000001_create_cache_table.php
|   |   |-- 0001_01_01_000002_create_jobs_table.php
|   |   |-- 2024_10_28_023329_create_ranks_table.php
|   |   |-- 2024_10_28_111111_create_users_table.php
|   |   |-- 2024_10_31_054859_add_energy_to_users.php
|   |   |-- 2024_11_01_063307_drop_colums_from_user.php
|   |   |-- 2024_11_01_070009_create_students_table.php
|   |   |-- 2024_11_01_070101_create_teachers_table.php
|   |   |-- 2024_11_15_131744_create_admins_table.php
|   |   |-- 2025_10_25_092743_create_courses_table.php
|   |   |-- 2025_10_25_092780_add_teacher_id_and_status_to_courses.php
|   |   |-- 2025_10_25_092803_create_lessons_table.php
|   |   |-- 2025_10_25_092824_create_contents_table.php
|   |   |-- 2025_10_25_092832_create_cards_table.php
|   |   |-- 2025_10_25_092853_create_blocks_table.php
|   |   |-- 2025_11_25_151651_create_student_content_progress_table.php
|   |   |-- 2025_11_26_064859_create_quizzes_table.php
|   |   |-- 2025_11_26_064908_create_quiz_questions_table.php
|   |   |-- 2025_11_26_065044_create_quiz_participants_table.php
|   |   |-- 2025_11_26_073830_create_quiz_question_choices_table.php
|   |   |-- 2025_11_26_221413_add_experience_from_contents_table.php
|   |   |-- 2025_11_26_221440_add_max_experience_from_quizzes_table.php
|   |   |-- 2025_11_26_221510_add_experience_got_from_quiz_participants_table.php
|   |   |-- 2025_11_28_224815_add_code_column_to_quizzes.php
|   |   |-- 2025_11_29_111930_add_interval_column_to_quizzes.php
|   |   |-- 2025_12_02_222132_add_status_to_quizzes_table.php
|   |   |-- 2025_12_05_231159_remove_key_from_students.php
|   |   `-- 2025_12_06_044848_add_profile_on_teachers.php
|   |-- seeders/
|   |   |-- AdminSeeder.php
|   |   |-- BlockSeeder.php
|   |   |-- CardSeeder.php
|   |   |-- ContentSeeder.php
|   |   |-- CourseSeeder.php
|   |   |-- DatabaseSeeder.php
|   |   |-- LessonSeeder.php
|   |   |-- QuizParticipantSeeder.php
|   |   |-- QuizQuestionChoiceSeeder.php
|   |   |-- QuizQuestionSeeder.php
|   |   |-- QuizSeeder.php
|   |   |-- RankSeeder.php
|   |   |-- StudentDummy.php
|   |   |-- StudentProgressSeeder.php
|   |   |-- StudentSeeder.php
|   |   |-- TeacherSeeder.php
|   |   `-- UserSeeder.php
|   `-- .gitignore
|-- public/
|   |-- assets/
|   |   `-- icons/
|   |       |-- sidebar-icons/
|   |       |   |-- accmanag.png
|   |       |   |-- community.png
|   |       |   |-- course.png
|   |       |   |-- home.png
|   |       |   |-- leaderboard.png
|   |       |   |-- logout.png
|   |       |   |-- profile.png
|   |       |   `-- pvp.png
|   |       |-- heart.png
|   |       |-- mascotss.png
|   |       |-- mascotsss.png
|   |       `-- plant.png
|   |-- images/
|   |   |-- 403.png
|   |   |-- 500.png
|   |   |-- crown1.png
|   |   |-- crown2.png
|   |   |-- crown3.png
|   |   |-- EROR.png
|   |   |-- heart.png
|   |   |-- leaderboard.png
|   |   |-- maskot.png
|   |   |-- quiz.png
|   |   |-- rank1.png
|   |   |-- rank2.png
|   |   |-- rank3.png
|   |   |-- user.png
|   |   `-- women.png
|   |-- .htaccess
|   |-- favicon.ico
|   |-- index.php
|   `-- robots.txt
|-- resources/
|   |-- css/
|   |   `-- app.css
|   |-- js/
|   |   |-- app.js
|   |   |-- bootstrap.js
|   |   `-- echo.js
|   `-- views/
|       |-- admin/
|       |   |-- manajemen-course/
|       |   |   |-- index.blade.php
|       |   |   |-- preview.blade.php
|       |   |   `-- show.blade.php
|       |   |-- manajemen-quiz/
|       |   |   |-- index.blade.php
|       |   |   `-- show.blade.php
|       |   `-- manajemen-teachers.blade.php
|       |-- auth/
|       |   |-- choose-role.blade.php
|       |   |-- login.blade.php
|       |   |-- register-teacher.blade.php
|       |   `-- register.blade.php
|       |-- community/
|       |   |-- index.blade.php
|       |   `-- post.blade.php
|       |-- components/
|       |   |-- dashboard-header.blade.php
|       |   |-- footer.blade.php
|       |   |-- navbar.blade.php
|       |   |-- sidebar.blade.php
|       |   `-- sidebaradmin.blade.php
|       |-- courses/
|       |   |-- student/
|       |   |   |-- detail.blade.php
|       |   |   |-- index.blade.php
|       |   |   `-- lesson.blade.php
|       |   `-- teacher/
|       |       |-- cards/
|       |       |   `-- show.blade.php
|       |       |-- lessons/
|       |       |   |-- partials/
|       |       |   |   |-- block.blade.php
|       |       |   |   |-- card.blade.php
|       |       |   |   `-- content.blade.php
|       |       |   `-- show.blade.php
|       |       |-- create.blade.php
|       |       |-- edit.blade.php
|       |       |-- index.blade.php
|       |       `-- show.blade.php
|       |-- dashboard/
|       |   |-- admin.blade.php
|       |   |-- profile.blade.php
|       |   |-- student.blade.php
|       |   `-- teacher.blade.php
|       |-- errors/
|       |   |-- 403.blade.php
|       |   |-- 404.blade.php
|       |   `-- 500.blade.php
|       |-- landing/
|       |   `-- index.blade.php
|       |-- layouts/
|       |   |-- dashboard.blade.php
|       |   |-- dashboardadmin.blade.php
|       |   `-- main.blade.php
|       |-- Leaderboard/
|       |   |-- items.blade.php
|       |   `-- leaderboard.blade.php
|       |-- profile/
|       |   |-- admin.blade.php
|       |   |-- student.blade.php
|       |   `-- teacher.blade.php
|       |-- quiz/
|       |   |-- create_question.blade.php
|       |   |-- create_quiz.blade.php
|       |   |-- edit_question.blade.php
|       |   |-- index.blade.php
|       |   |-- manage.blade.php
|       |   |-- quiz_monitoring.blade.php
|       |   |-- quiz_running.blade.php
|       |   `-- register.blade.php
|       |-- ranks/
|       |   `-- index.blade.php
|       |-- TESTING/
|       |   |-- card.blade.php
|       |   |-- change_content.blade.php
|       |   `-- test_delete.blade.php
|       |-- welcome/
|       |   |-- student.blade.php
|       |   `-- teacher.blade.php
|       |-- aavatarajaja.blade.php
|       |-- avatar.blade.php
|       |-- loginpage.blade.php
|       |-- post_question.blade.php
|       |-- ranks.blade.php
|       |-- test_websocket.blade.php
|       |-- upload.blade.php
|       |-- users.blade.php
|       |-- view_courses.blade.php
|       `-- welcome.blade.php
|-- routes/
|   |-- channels.php
|   |-- console.php
|   `-- web.php
|-- storage/
|   |-- app/
|   |   |-- private/
|   |   |   `-- .gitignore
|   |   |-- public/
|   |   |   `-- .gitignore
|   |   `-- .gitignore
|   |-- framework/
|   |   |-- cache/
|   |   |   |-- data/
|   |   |   |   `-- .gitignore
|   |   |   `-- .gitignore
|   |   |-- sessions/
|   |   |   `-- .gitignore
|   |   |-- testing/
|   |   |   `-- .gitignore
|   |   |-- views/
|   |   |   `-- .gitignore
|   |   `-- .gitignore
|   `-- logs/
|       `-- .gitignore
|-- tests/
|   |-- Feature/
|   |   `-- ExampleTest.php
|   |-- Unit/
|   |   `-- ExampleTest.php
|   `-- TestCase.php
|-- .editorconfig
|-- .env.example
|-- .gitattributes
|-- .gitignore
|-- artisan
|-- composer.json
|-- composer.lock
|-- package-lock.json
|-- package.json
|-- phpunit.xml
|-- postcss.config.js
|-- README.md
|-- table.md
|-- tailwind.config.js
`-- vite.config.js
```
