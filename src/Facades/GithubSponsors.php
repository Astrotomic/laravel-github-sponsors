<?php

namespace Astrotomic\GithubSponsors\Facades;

use Astrotomic\GithubSponsors\Clients\Login;
use Astrotomic\GithubSponsors\Clients\Organization;
use Astrotomic\GithubSponsors\Clients\User;
use Astrotomic\GithubSponsors\Clients\Viewer;
use Astrotomic\GithubSponsors\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Viewer viewer()
 * @method static Login login(string $login)
 * @method static User user(string $login)
 * @method static Organization organization(string $login)
 *
 * @see Factory
 */
class GithubSponsors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Factory::class;
    }
}
