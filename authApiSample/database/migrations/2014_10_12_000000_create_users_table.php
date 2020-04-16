<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id');
            $table->primary('id');
            $table->string('name');
            $table->enum('role', array(
                config('const_user.ROLE_CUSTOMER'),
                config('const_user.ROLE_STORE'),
                config('const_user.ROLE_ADMIN')
            ))->index();
            $table->enum('status', array(
                config('const_user.STATUS_ACTIVE'),
                config('const_user.STATUS_CONFIRMING'),
                config('const_user.STATUS_SUSPEND')
            ));
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
}
