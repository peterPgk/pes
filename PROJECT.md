###Installation instructions

- Run composer install to install all dependencies.
- Copy `.env.example` as `.env` where to store all sensitive information.
- Create empty database, and populate proper data in `.env` for connecting to MYSQL.
- in Console, run 

    ```php artisan migrate --seed```
    
  for creating all necessary tables and seeding some data in the database
 - Run 
 
    ```php artisan key:generate``` for generation of encryption key
-

###Packages
- Spatie  [LaravelPermissions](https://github.com/spatie/laravel-permission) package. Very powerful package for managing roles and permissions. Here I am using roles via permissions.
- [Laravel-Phone](https://github.com/spatie/laravel-permission) package for handling phone validation and display.

###Extra info
We are using build in Basic Auth functionality in Laravel, little tweaking it.
For instance email verifications and confirmations are disabled.

In order to make things as suppose to be, I made new migration for changing users table structure instead of editing existing one (came with Auth functionality).