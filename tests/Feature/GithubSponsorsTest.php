<?php

use Astrotomic\GithubSponsors\Facades\GithubSponsors;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\LazyCollection;
use Pest\Expectation;

it('retrieves sponsors for authenticated user')
    ->expect(fn() => GithubSponsors::fromViewer()->all())
    ->toBeInstanceOf(Collection::class)
    ->not->toBeEmpty()
    ->each(static function(Expectation $value): void {
        $value
            ->toBeInstanceOf(Fluent::class)
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors for given user')
    ->expect(fn() => GithubSponsors::fromUser('Gummibeer')->all())
    ->toBeInstanceOf(Collection::class)
    ->not->toBeEmpty()
    ->each(static function(Expectation $value): void {
        $value
            ->toBeInstanceOf(Fluent::class)
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors for given organization')
    ->expect(fn() => GithubSponsors::fromOrganization('larabelles')->all())
    ->toBeInstanceOf(Collection::class)
    ->not->toBeEmpty()
    ->each(static function(Expectation $value): void {
        $value
            ->toBeInstanceOf(Fluent::class)
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors with custom fields')
    ->expect(fn() => GithubSponsors::fromViewer()->select('login', 'databaseId')->all())
    ->toBeInstanceOf(Collection::class)
    ->not->toBeEmpty()
    ->each(static function(Expectation $value): void {
        $value
            ->toBeInstanceOf(Fluent::class)
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString()
            ->databaseId->toBeInt()->toBeGreaterThan(0);
    });

it('retrieves sponsors lazyly')
    ->expect(fn() => GithubSponsors::fromViewer()->cursor())
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function(Expectation $value): void {
        $value
            ->toBeInstanceOf(Fluent::class)
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('checks if someone is a sponsor')
    ->expect(fn() => GithubSponsors::fromOrganization('larabelles')->isSponsor('Gummibeer'))
    ->toBeTrue();

it('checks if someone is a sponsor is casesensitive')
    ->expect(fn() => GithubSponsors::fromOrganization('larabelles')->isSponsor('gummibeer'))
    ->toBeFalse();

it('checks if someone is not a sponsor')
    ->expect(fn() => GithubSponsors::fromViewer()->isSponsor('Gummibeer'))
    ->toBeFalse();

it('checks if an organization is a sponsor')
    ->expect(fn() => GithubSponsors::fromViewer()->isSponsor('spatie'))
    ->toBeTrue();
