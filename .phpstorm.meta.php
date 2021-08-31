<?php

namespace PHPSTORM_META {

    expectedArguments(
        \Astrotomic\GithubSponsors\Graphql::send(),
        0,
        'viewer',
        'login',
        'user',
        'organization'
    );

    expectedArguments(
        \Astrotomic\GithubSponsors\Graphql::send(),
        1,
        'isSponsoredBy',
        'isSponsoring',
        'sponsorsCount',
        'sponsors'
    );

}