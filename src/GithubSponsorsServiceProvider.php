<?php

namespace Astrotomic\GithubSponsors;

use Illuminate\Support\ServiceProvider;

class GithubSponsorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Graphql::class);
        $this->app->when(Graphql::class)
            ->needs('$token')
            ->giveConfig('services.github.sponsors_token');

        $this->app->singleton(Factory::class);
    }
}
