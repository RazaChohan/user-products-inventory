<?php

namespace App\Providers;
use Dal\Interfaces\UserInterface;
use Dal\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Bind all interfaces with repositories
        $this->app->bind(UserInterface::class, UserRepository::class);
    }
}
