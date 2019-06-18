<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');

            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('employee_id')->nullable()->after('email')->unique();
            $table->date('date_of_birth')->nullable()->after('password');
            $table->tinyInteger('in_probation')->unsigned()->after('date_of_birth')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable()->after('email');

            $table->dropColumn('phone');
            $table->dropColumn('address');
            $table->dropColumn('employee_id');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('in_probation');
        });
    }
}
