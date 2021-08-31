<?php

namespace Astrotomic\GithubSponsors\Clients;

use Astrotomic\GithubSponsors\Contracts\Client;
use Astrotomic\GithubSponsors\Graphql;
use Generator;
use Illuminate\Support\LazyCollection;

class Login implements Client
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
        $result = $this->graphql->send('login', 'isSponsoredBy', [
            'account' => $this->login,
            'sponsor' => $sponsor,
        ]);

        return $result['user']['isSponsoredBy']
            ?? $result['organization']['isSponsoredBy'];
    }

    public function isSponsoring(string $account): bool
    {
        $result = $this->graphql->send('login', 'isSponsoring', [
            'account' => $account,
            'sponsor' => $this->login,
        ]);

        return $result['user']['isSponsoredBy']
            ?? $result['organization']['isSponsoredBy'];
    }

    public function sponsorsCount(): int
    {
        $result = $this->graphql->send('login', 'sponsorsCount', [
            'account' => $this->login,
        ]);

        return $result['user']['sponsorshipsAsMaintainer']['totalCount']
            ?? $result['organization']['sponsorshipsAsMaintainer']['totalCount'];
    }

    public function hasSponsors(): bool
    {
        return $this->sponsorsCount() > 0;
    }

    public function sponsors(array $fields = ['login'], array $userFields = [], array $organizationFields = []): LazyCollection
    {
        return LazyCollection::make(function () use ($fields, $userFields, $organizationFields): Generator {
            $userCursor = null;
            $organizationCursor = null;

            do {
                $data = $this->graphql->send('login', 'sponsors', [
                    'account' => $this->login,
                    'userCursor' => $userCursor,
                    'organizationCursor' => $organizationCursor,
                    'user_fields' => implode(PHP_EOL, array_merge($fields, $userFields)),
                    'organization_fields' => implode(PHP_EOL, array_merge($fields, $organizationFields)),
                ]);

                $userCursor = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['endCursor'] ?? null;
                $organizationCursor = $data['organization']['sponsorshipsAsMaintainer']['pageInfo']['endCursor'] ?? null;

                $hasNextPage = $data['user']['sponsorshipsAsMaintainer']['pageInfo']['hasNextPage']
                    ?? $data['organization']['sponsorshipsAsMaintainer']['pageInfo']['hasNextPage'];

                yield from array_column(
                    $data['user']['sponsorshipsAsMaintainer']['nodes']
                    ?? $data['organization']['sponsorshipsAsMaintainer']['nodes'],
                    'sponsorEntity'
                );
            } while ($hasNextPage && ($userCursor || $organizationCursor));
        });
    }
}
