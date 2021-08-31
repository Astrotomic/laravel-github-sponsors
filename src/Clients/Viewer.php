<?php

namespace Astrotomic\GithubSponsors\Clients;

use Astrotomic\GithubSponsors\Contracts\Client;
use Astrotomic\GithubSponsors\Graphql;
use Generator;
use Illuminate\Support\LazyCollection;

class Viewer implements Client
{
    protected Graphql $graphql;

    public function __construct(Graphql $graphql)
    {
        $this->graphql = $graphql;
    }

    public function isSponsoredBy(string $sponsor): bool
    {
        $result = $this->graphql->send('viewer', 'isSponsoredBy', [
            'sponsor' => $sponsor,
        ]);

        return $result['user']['isSponsoringViewer']
            ?? $result['organization']['isSponsoringViewer'];
    }

    public function isSponsoring(string $account): bool
    {
        $result = $this->graphql->send('viewer', 'isSponsoring', [
            'account' => $account,
        ]);

        return $result['user']['viewerIsSponsoring']
            ?? $result['organization']['viewerIsSponsoring'];
    }

    public function sponsorsCount(): int
    {
        $result = $this->graphql->send('viewer', 'sponsorsCount');

        return $result['viewer']['sponsorshipsAsMaintainer']['totalCount'];
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
                $data = $this->graphql->send('viewer', 'sponsors', [
                    'cursor' => $cursor,
                    'user_fields' => implode(PHP_EOL, array_merge($fields, $userFields)),
                    'organization_fields' => implode(PHP_EOL, array_merge($fields, $organizationFields)),
                ])['viewer']['sponsorshipsAsMaintainer'];

                $cursor = $data['pageInfo']['endCursor'];

                yield from array_column($data['nodes'], 'sponsorEntity');
            } while ($data['pageInfo']['hasNextPage'] && $cursor);
        });
    }
}
