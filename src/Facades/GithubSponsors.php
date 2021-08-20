<?php

namespace Astrotomic\GithubSponsors\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Astrotomic\GithubSponsors\GithubSponsors fromViewer()
 * @method static \Astrotomic\GithubSponsors\GithubSponsors fromUser(string $login)
 * @method static \Astrotomic\GithubSponsors\GithubSponsors fromOrganization(string $login)
 *
 * @see \Astrotomic\GithubSponsors\GithubSponsors
 */
class GithubSponsors extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Astrotomic\GithubSponsors\GithubSponsors::class;
    }
}