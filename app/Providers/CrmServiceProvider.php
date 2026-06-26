<?php

namespace App\Providers;


use App\Models\User;
use App\Observers\UserObserver;
use App\Repositories\Interfaces\LeadRepositoryInterface;
use App\Repositories\LeadRepository;
use Illuminate\Support\ServiceProvider;

class CrmServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // bind interface to implementation
        $this->app->bind(
            LeadRepositoryInterface::class,
            LeadRepository::class
        );
    }

    public function boot(): void
    {
         User::observe(UserObserver::class);
    }
}
