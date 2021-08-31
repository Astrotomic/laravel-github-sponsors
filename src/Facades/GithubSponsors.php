<?php

namespace Astrotomic\GithubSponsors\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Astrotomic\GithubSponsors\Clients\Viewer viewer()
 * @method static \Astrotomic\GithubSponsors\Clients\Login login(string $login)
 * @method static \Astrotomic\GithubSponsors\Clients\User user(string $login)
 * @method static \Astrotomic\GithubSponsors\Clients\Organization organization(string $login)
 *
 * @see \Astrotomic\GithubSponsors\Factory
 */
class GithubSponsors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Astrotomic\GithubSponsors\Factory::class;
    }
}