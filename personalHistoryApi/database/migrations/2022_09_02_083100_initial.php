<?php

use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)
            ->table('user_accounts', function (Blueprint $collection) {
                $collection->unique('user_id');
                $collection->unique(['auth_info.email', 'auth_info.gmail', 'auth_info.twitter_id']);
                $collection->index('update_at');
            });
        Schema::connection($this->connection)
            ->table('email_auth_temps', function (Blueprint $collection) {
                $collection->unique('email');
            });
        Schema::connection($this->connection)
            ->table('histories', function (Blueprint $collection) {
                $collection->index('user_account_id');
                $collection->index('history_category_id');
            });
        Schema::connection($this->connection)
            ->table('history_categories', function (Blueprint $collection) {
                $collection->index('user_account_id');
                $collection->index('default_setting_flag');
            });
        Schema::connection($this->connection)
            ->table('history_category_settings', function (Blueprint $collection) {
                $collection->index('user_account_id');
            });
        Schema::connection($this->connection)
            ->table('histories', function (Blueprint $collection) {
                $collection->unique(['user_account_id', 'category_id']);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)
            ->table('user_accounts', function (Blueprint $collection) {
                $collection->drop();
            });
        Schema::connection($this->connection)
            ->table('email_auth_temps', function (Blueprint $collection) {
                $collection->drop();
            });
        Schema::connection($this->connection)
            ->table('histories', function (Blueprint $collection) {
                $collection->drop();
            });
        Schema::connection($this->connection)
            ->table('history_categories', function (Blueprint $collection) {
                $collection->drop();
            });
        Schema::connection($this->connection)
            ->table('history_category_settings', function (Blueprint $collection) {
                $collection->drop();
            });
    }
};
