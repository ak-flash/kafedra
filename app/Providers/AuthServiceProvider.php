<?php

namespace App\Providers;


use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use App\Policies\ActivityPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            // Get model name with sub folder
            /*$inFolder = glob("..\App\Policies\*");
            $inSubFolder = glob("..\App\Policies\*\*");*/

            $pieces = explode('Models', $modelClass);
            return 'App\\Policies' . $pieces[1] . 'Policy';

        });



    }
}
