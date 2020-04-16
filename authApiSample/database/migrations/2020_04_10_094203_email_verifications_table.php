<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('user_id')->unique();
            $table->string('email')->unique();
            $table->string('token');
            $table->dateTime('expires_at');
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
        Schema::dropIfExists('email_verifications');
    }
}
