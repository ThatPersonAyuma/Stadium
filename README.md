After cloning run this: <br>
1. cp .env.example .env <br>
2. php artisan key:generate <br>
3. composer install <br>
4. npm install <br>
5. npm build <br>
Don't forget to change the database setting in .env file <br>
After that run php artisan migrte <br>
<br>
*Note if yougot error on storage use php artisan storage:link<br>
as it will create a symbolic link (symlink) from storage/app/public to public/storage