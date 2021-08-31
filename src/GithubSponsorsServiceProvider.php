<?php

namespace Astrotomic\GithubSponsors;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class GithubSponsorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Graphql::class);
        $this->app->when(Graphql::class)
            ->needs('$token')
            ->give(static function (Container $container): string {
                return $container->get('config')->get('services.github.sponsors_token');
            });

        $this->app->singleton(Factory::class);
    }
}
