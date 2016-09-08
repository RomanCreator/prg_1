<?php

namespace App\Providers;


use App\CallBackRequest;
use App\CallCenterPhoneNumber;
use App\District;
use App\Hospital;
use App\Price;
use App\Research;
use App\Role;
use App\RolePermission;
use App\StaticPage;
use App\TomographType;
use App\TypeResearch;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Policies\ModelPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => ModelPolicy::class,
        RolePermission::class => ModelPolicy::class,
        Role::class => ModelPolicy::class,
        StaticPage::class => ModelPolicy::class,
        Research::class => ModelPolicy::class,
        Hospital::class => ModelPolicy::class,
        Price::class => ModelPolicy::class,
        District::class => ModelPolicy::class,
        TypeResearch::class => ModelPolicy::class,
        TomographType::class => ModelPolicy::class,
        CallBackRequest::class => ModelPolicy::class,
        CallCenterPhoneNumber::class => ModelPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //

    }
}
