<?php

namespace Astrotomic\GithubSponsors;

use Astrotomic\GithubSponsors\Clients\Login;
use Astrotomic\GithubSponsors\Clients\Organization;
use Astrotomic\GithubSponsors\Clients\User;
use Astrotomic\GithubSponsors\Clients\Viewer;

class Factory
{
    public function viewer(): Viewer
    {
        return app(Viewer::class);
    }

    public function login(string $login): Login
    {
        return app(Login::class, [
            'login' => $login,
        ]);
    }

    public function user(string $login): User
    {
        return app(User::class, [
            'login' => $login,
        ]);
    }

    public function organization(string $login): Organization
    {
        return app(Organization::class, [
            'login' => $login,
        ]);
    }
}