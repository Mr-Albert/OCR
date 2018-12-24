<?php

namespace App\Providers;

use App\Role;
use App\User;

use App\SourceGroup;
use App\Policies\SourceGroupPolicy;

use App\SourceCodeModel;
use App\Policies\SourceCodePolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        SourceGroup::class => SourceGroupPolicy::class,
        SourceCodeModel::class => SourceCodePolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
