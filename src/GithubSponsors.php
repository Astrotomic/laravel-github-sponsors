<?php

namespace Astrotomic\GithubSponsors;

use Astrotomic\GraphqlQueryBuilder\Graph;
use Astrotomic\GraphqlQueryBuilder\Query;
use Exception;
use Generator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;

class GithubSponsors
{
    protected string $fromName;
    protected array $fromArguments = [];
    protected array $select = [
        'login'
    ];
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function fromViewer(): self
    {
        $this->fromName = 'viewer';
        $this->fromArguments = [];

        return $this;
    }

    public function fromUser(string $login): self
    {
        $this->fromName = 'user';
        $this->fromArguments = [
            'login' => $login,
        ];

        return $this;
    }

    public function fromOrganization(string $login): self
    {
        $this->fromName = 'organization';
        $this->fromArguments = [
            'login' => $login,
        ];

        return $this;
    }

    public function select(string ...$fields): self
    {
        $this->select = $fields;

        return $this;
    }

    public function all(): Collection
    {
        return $this->cursor()->collect();
    }

    public function isSponsor(string $login): bool
    {
        return $this
            ->select('login')
            ->cursor()
            ->contains(fn(Fluent $sponsor): bool => $sponsor->login === $login);
    }

    public function cursor(): LazyCollection
    {
        return LazyCollection::make(function (): Generator {
            $cursor = null;

            do {
                $data = $this->request($this->query($cursor));
                $cursor = $data['pageInfo']['endCursor'];

                foreach (data_get($data, 'nodes.*.sponsorEntity') as $sponsor) {
                    yield new Fluent($sponsor);
                }
            } while ($data['pageInfo']['hasNextPage'] ?? false);
        });
    }

    protected function query(?string $after = null): string
    {
        return (string) Graph::query(
            Query::from($this->fromName)
                ->with($this->fromArguments)
                ->select(
                    Query::from('sponsorshipsAsMaintainer')
                        ->with(array_filter(['first' => 100, 'after' => $after]))
                        ->select(
                            Query::from('pageInfo')->select('hasNextPage', 'endCursor'),
                            Query::from('nodes')->select(
                                Query::from('sponsorEntity')->select(
                                    '__typename',
                                    Query::for('User')->select(...$this->select),
                                    Query::for('Organization')->select(...$this->select),
                                )
                            )
                        )
                )
        );
    }

    protected function request(string $query): array
    {
        $response = Http::baseUrl('https://api.github.com')
            ->accept('application/vnd.github.v3+json')
            ->withUserAgent('astrotomic/laravel-github-sponsors')
            ->withOptions(['http_errors' => true])
            ->withToken($this->token)
            ->post('/graphql', ['query' => $query])
            ->json();

        if (array_key_exists('errors', $response)) {
            throw new Exception(Arr::first($response['errors'])['message']);
        }

        return data_get($response, "data.{$this->fromName}.sponsorshipsAsMaintainer");
    }
}