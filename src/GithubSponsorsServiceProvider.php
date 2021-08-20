<?php

namespace Astrotomic\GithubSponsors;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class GithubSponsorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GithubSponsors::class, function(Container $app): GithubSponsors {
            return new GithubSponsors(
                $app->make('config')->get('services.github.sponsors_token')
            );
        });
    }
}