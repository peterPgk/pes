###Installation instructions

- Run `composer install` to install all dependencies.
- Copy `.env.example` as `.env` where to store all sensitive information (default `.env` file is included).
- Create empty database (by default=pes), and populate proper data in `.env` file in Database section to be able to connect to MySQL server.
- In Console, run 

    ```php artisan migrate --seed```
    
  to create all necessary tables and seeding some data in the database. By default roles `manager` and `staff member` are created, and
  and two users with these roles.
  The `sql.dump` file is also included in the root of the project.
  The credentials can be checked in `.env` file under User information or in `UsersSeeder` class in `database\seeds` folder.
- Run 
 
    ```php artisan key:generate``` 
    
    for generation of encryption key

###Other info
- Make sure that `storage` and `bootstrap/cache` directories have proper rights and are writable.
- Project root directory is `public`, so any virtual hosts should point there.
- To be able to have Code completion and to follow functions in editor, run 

    ```php artisan ide-helper:generate```
 
    to generate needed files. (Tested in PHPStorm)

###Packages used
- Default, built in `Basic Auth` package provided by Laravel.
- Spatie  [LaravelPermissions](https://github.com/spatie/laravel-permission) package. Very powerful package for managing roles and permissions. Here I am using roles via permissions.
- [Laravel-Phone](https://github.com/spatie/laravel-permission) package for handling phone validation and display.
- [laracasts/flash](https://github.com/laracasts/flash) for basic session flash messages.
- [drfraker/snipe-migrations](https://github.com/drfraker/snipe-migrations) for speed up PHPHUnit tests.

###Extra info
In order to make things in proper way, I made new migration for changing users table structure instead of editing existing one (which came with Auth functionality).

Limitation for the staff member to be able to edit only particular fields is implemented with `TranformRequestByRole` middleware.

Available Roles are served to the views with help of ViewComposer `(App\Http\Views\Composers\RoleComposer)` `(App\Providers\ViewServiceProvider)`

###Tests
For feature and acceptance tests Laravel uses real database with real database connection. 
Usually, for better performance, this is an `in_memory` `sqlite` database.

To setup tests to be run properly we need to force `PHPUnit` to use our `phpunit.xml` file from the root of the project 
as default configuration file.

In this file we are overwriting some environment variables to be able to use different database for testing.

**This is important, because otherwise we will destroy our real database!!**

I set it up two different type of connections there, one using in_memory `sqlite` database (commented one),
other will use `mysql` real test database. 

In mysql approach, we need to create new database named `pes_test` (or change name in `.env` file). And depending of current
configuration, is better tests to be run one by one.

This test connection by default will use the same credentials as real connection. This can be changed in `database.php` 
under `mysql_testing` connection
