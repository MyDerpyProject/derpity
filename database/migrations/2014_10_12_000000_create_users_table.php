<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userID');
            $table->string('username')->unique();
			$table->string('password');
            $table->string('email')->unique();
            $table->string('FirstName');
            $table->string('LastName');
			$table->string('birthday');
            $table->enum('gender', array('m','f'));
            $table->string('city');
            $table->string('state');
            $table->string('interests');
            $table->string('bio');
            $table->string('ProfilePic');				
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
