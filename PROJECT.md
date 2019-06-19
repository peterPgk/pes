###Installation instructions

- Run composer install to install all dependencies.
- Copy `.env.example` as `.env` where to store all sensitive information.
- Create empty database, and populate proper data in `.env` for connecting to MYSQL.
- in Console, run 

    ```php artisan migrate --seed```
    
  for creating all necessary tables and seeding some data in the database
- Run 
 
    ```php artisan key:generate``` for generation of encryption key
- Make sure that `storage` and `bootstrap/cache` directories have proper rights and are writable 

###Packages
- Default built in Basic Auth package provided by Laravel and built in its core.
- Spatie  [LaravelPermissions](https://github.com/spatie/laravel-permission) package. Very powerful package for managing roles and permissions. Here I am using roles via permissions.
- [Laravel-Phone](https://github.com/spatie/laravel-permission) package for handling phone validation and display.
- [laracasts/flash](https://github.com/laracasts/flash) for basic session flash messages.
- [drfraker/snipe-migrations](https://github.com/drfraker/snipe-migrations) for speed up PHPHUnit tests.

###Extra info
We are using build in Basic Auth functionality in Laravel, little tweaking it.
For instance email verifications and confirmations are disabled.

In order to make things as suppose to be, I made new migration for changing users table structure instead of editing existing one (came with Auth functionality).

Limitation for the staff member to be able to edit only particular fields is implemented with `TranformRequestByRole` middleware.

We use ViewComposer `(App\Http\Views\Composers\RoleComposer)` to serve all available roles to some views `(App\Providers\ViewServiceProvider)`

###Tests
For feature and acceptance tests Laravel uses real database and database connection for running them. 
Usually, this is in_memory `sqlite` database, for better performance.

To setup this we need to force `PHPUnit` to use our `phpunit.xml` file from root of the project as default configuration file.
In this file we are overwriting some environment variables to be able to use different database for testing.

**This is important, because otherwise we can destroy our real database!!**

I set it up two different type of connections there, one using in_memory `sqlite` database (commented one),
other will use `mysql` real database. If we decide to use second approach (mysql), we need to create new database
`pes_test`.

This test connection by default will use the same credentials as real connection. If we need to change this we can do it
in `database.php` under `mysql_testing` connection
