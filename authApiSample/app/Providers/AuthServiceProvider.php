<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Carbon\Carbon;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        // アクセストークンの制限時間を4時間に設定している
        Passport::tokensExpireIn(Carbon::now()->addHours(4));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addHours(4));
        // リフレッシュトークンの制限時間を30日に設定している
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
