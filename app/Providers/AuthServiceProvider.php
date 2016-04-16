<?php

namespace CALwebtool\Providers;

use CALwebtool\FormDefinition;
use CALwebtool\Policies\FormPolicy;
use CALwebtool\Policies\SubmissionPolicy;
use CALwebtool\Submission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'CALwebtool\Model' => 'CALwebtool\Policies\ModelPolicy',
        Submission::class => SubmissionPolicy::class,
        FormDefinition::class => FormPolicy::class
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
