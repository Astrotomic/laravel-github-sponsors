<?php

use Astrotomic\GithubSponsors\GithubSponsorsServiceProvider;
use Orchestra\Testbench\TestCase;

uses(TestCase::class)->in('Feature');

uses()->beforeEach(function (): void {
    $this->app->register(GithubSponsorsServiceProvider::class);

    config()->set('services.github.sponsors_token', env('GH_SPONSORS_TOKEN'));
})->in('Feature');
