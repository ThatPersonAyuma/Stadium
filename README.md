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
|   |   `-- ContentType.php
|   |-- Helpers/
|   |   `-- FileHelper.php
|   |-- Http/
|   |   `-- Controllers/
|   |       |-- BlockController.php
|   |       |-- CardController.php
|   |       |-- ContentController.php
|   |       |-- CourseController.php
|   |       |-- LessonController.php
|   |       |-- UserAvatarController.php
|   |       `-- UserController.php
|   |-- Models/
|   |   |-- Block.php
|   |   |-- Card.php
|   |   |-- Content.php
|   |   |-- Course.php
|   |   |-- Lesson.php
|   |   |-- Rank.php
|   |   `-- User.php
|   `-- Providers/
|       `-- AppServiceProvider.php
|-- bootstrap/
|   `-- app.php
|-- config/
|-- database/
|   |-- factories/
|   |   `-- UserFactory.php
|   |-- migrations/
|   |   |-- 0001_01_01_000001_create_cache_table.php
|   |   |-- 0001_01_01_000002_create_jobs_table.php
|   |   |-- 2025_10_25_092743_create_courses_table.php
|   |   |-- 2025_10_25_092803_create_lessons_table.php
|   |   |-- 2025_10_25_092824_create_contents_table.php
|   |   |-- 2025_10_25_092832_create_cards_table.php
|   |   |-- 2025_10_25_092853_create_blocks_table.php
|   |   |-- 2025_10_28_023329_create_ranks_table.php
|   |   |-- 2025_10_28_111111_create_users_table.php
|   |   `-- 2025_10_31_054859_add_energy_to_users.php
|   `-- seeders/
|       |-- BlockSeeder.php
|       |-- CardSeeder.php
|       |-- ContentSeeder.php
|       |-- CourseSeeder.php
|       |-- LessonSeeder.php
|       |-- RankSeeder.php
|       `-- UserSeeder.php
|-- public/
|   |-- build/
|   |-- favicon.ico
|   `-- index.php
|-- resources/
|   |-- css/
|   |   `-- app.css
|   |-- js/
|   |   |-- app.js
|   |   `-- bootstrap.js
|   `-- views/
|       |-- articles/
|       |   |-- index.blade.php
|       |   `-- show.blade.php
|       |-- auth/
|       |   |-- login.blade.php
|       |   `-- register.blade.php
|       |-- community/
|       |   |-- index.blade.php
|       |   `-- post.blade.php
|       |-- components/
|       |   |-- card.blade.php
|       |   |-- footer.blade.php
|       |   |-- hero.blade.php
|       |   `-- navbar.blade.php
|       |-- courses/
|       |   |-- index.blade.php
|       |   |-- quiz.blade.php
|       |   `-- show.blade.php
|       |-- dashboard/
|       |   |-- index.blade.php
|       |   |-- profile.blade.php
|       |   |-- student.blade.php
|       |   `-- teacher.blade.php
|       |-- errors/
|       |   `-- 404.blade.php
|       |-- landing/
|       |   `-- index.blade.php
|       |-- layouts/
|       |   |-- auth.blade.php
|       |   |-- dashboard.blade.php
|       |   `-- main.blade.php
|       |-- ranks/
|       |   `-- index.blade.php
|       |-- welcome/
|       |   |-- student.blade.php
|       |   `-- teacher.blade.php
|       |-- welcome.blade.php
|       |-- aavatarajaja.blade.php
|       |-- avatar.blade.php
|       |-- loginpage.blade.php
|       |-- ranks.blade.php
|       |-- upload.blade.php
|       |-- users.blade.php
|       `-- view_courses.blade.php
|-- routes/
|   |-- console.php
|   `-- web.php
|-- storage/
|-- tests/
|-- artisan
|-- composer.json
|-- package.json
|-- phpunit.xml
|-- vite.config.js
`-- README.md
```
