<?php

namespace Astrotomic\GithubSponsors\Clients;

use Astrotomic\GithubSponsors\Contracts\Client;
use Astrotomic\GithubSponsors\Graphql;
use Generator;
use Illuminate\Support\LazyCollection;

class User implements Client
{
    protected string $login;
    protected Graphql $graphql;

    public function __construct(string $login, Graphql $graphql)
    {
        $this->login = $login;
        $this->graphql = $graphql;
    }

    public function isSponsoredBy(string $sponsor): bool
    {
        $result = $this->graphql->send('user', 'isSponsoredBy', [
            'account' => $this->login,
            'sponsor' => $sponsor,
        ]);

        return $result['user']['isSponsoredBy'];
    }

    public function isSponsoring(string $account): bool
    {
        $result = $this->graphql->send('user', 'isSponsoring', [
            'account' => $account,
            'sponsor' => $this->login,
        ]);

        return $result['user']['isSponsoredBy']
            ?? $result['organization']['isSponsoredBy'];
    }

    public function sponsorsCount(): int
    {
        $result = $this->graphql->send('user', 'sponsorsCount', [
            'account' => $this->login,
        ]);

        return $result['user']['sponsorshipsAsMaintainer']['totalCount'];
    }

    public function hasSponsoringEnabled(): bool
    {
        $result = $this->graphql->send('user', 'hasSponsoringEnabled', [
            'account' => $this->login,
        ]);

        return $result['user']['hasSponsorsListing'];
    }

    public function hasSponsors(): bool
    {
        return $this->sponsorsCount() > 0;
    }

    public function sponsors(array $fields = ['login'], array $userFields = [], array $organizationFields = []): LazyCollection
    {
        return LazyCollection::make(function () use ($fields, $userFields, $organizationFields): Generator {
            $cursor = null;

            do {
                $data = $this->graphql->send('user', 'sponsors', [
                    'account' => $this->login,
                    'cursor' => $cursor,
                    'user_fields' => implode(PHP_EOL, array_merge($fields, $userFields)),
                    'organization_fields' => implode(PHP_EOL, array_merge($fields, $organizationFields)),
                ]);

                $cursor = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['endCursor'];

                $hasNextPage = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['hasNextPage'];

                yield from array_column($data['user']['sponsorshipsAsMaintainer']['nodes'], 'sponsorEntity');
            } while ($hasNextPage && $cursor);
        });
    }
}
