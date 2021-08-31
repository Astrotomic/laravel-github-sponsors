<?php

namespace Astrotomic\GithubSponsors;

use Astrotomic\GithubSponsors\Exceptions\QueryErrorsException;
use Illuminate\Support\Facades\Http;
use OutOfRangeException;

class Graphql
{
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function send(string $group, string $name, array $variables = []): array
    {
        $response = Http::baseUrl('https://api.github.com')
            ->asJson()
            ->accept('application/vnd.github.v4+json')
            ->withHeaders(['User-Agent' => 'astrotomic/laravel-github-sponsors'])
            ->withOptions(['http_errors' => true])
            ->withToken($this->token)
            ->post('/graphql', [
                'query' => $this->query($group, $name, $variables),
                'variables' => $variables,
            ])
            ->json();

        if (empty($response['data']) && array_key_exists('errors', $response)) {
            throw QueryErrorsException::fromErrors($response['errors']);
        }

        return $response['data'];
    }

    protected function query(string $group, string $name, array $variables): string
    {
        $filepath = __DIR__."/../queries/{$group}/{$name}.graphql";

        if (! file_exists($filepath)) {
            throw new OutOfRangeException();
        }

        $query = file_get_contents($filepath);

        return preg_replace_callback(
            '/{\s+([a-z_]+)\s+}/',
            fn (array $hit): string => '{'.PHP_EOL.($variables[$hit[1]] ?? $hit[1]).PHP_EOL.'}',
            $query
        );
    }
}
