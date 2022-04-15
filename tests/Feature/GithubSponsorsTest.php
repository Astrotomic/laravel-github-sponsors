<?php

use Astrotomic\GithubSponsors\Facades\GithubSponsors;
use Illuminate\Support\LazyCollection;
use Pest\Expectation;

it('retrieves sponsors for authenticated user')
    ->expect(fn () => GithubSponsors::viewer()->sponsors())
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors for given user')
    ->expect(fn () => GithubSponsors::user('Gummibeer')->sponsors())
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors for given organization')
    ->expect(fn () => GithubSponsors::organization('larabelles')->sponsors())
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors for given login name')
    ->expect(fn () => GithubSponsors::login('Gummibeer')->sponsors())
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();
    });

it('retrieves sponsors with custom fields')
    ->expect(fn () => GithubSponsors::viewer()->sponsors(['login', 'databaseId']))
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString()
            ->databaseId->toBeInt()->toBeGreaterThan(0);
    });

it('retrieves sponsors with custom fields for users only')
    ->expect(fn () => GithubSponsors::viewer()->sponsors(['login'], ['company']))
    ->toBeInstanceOf(LazyCollection::class)
    ->not->toBeEmpty()
    ->each(static function (Expectation $value): void {
        $value
            ->toBeArray()
            ->__typename->toBeString()->toBeIn(['User', 'Organization'])
            ->login->toBeString();

        if ($value->value['__typename'] === 'User') {
            if ($value->value['company'] === null) {
                $value->company->toBeNull();
            } else {
                $value->company->toBeString();
            }
        }
    });

it('checks if someone is a sponsor of authenticated user')
    ->expect(fn () => GithubSponsors::viewer()->isSponsoredBy('mattstauffer'))
    ->toBeTrue();

it('checks if someone is a sponsor of an user')
    ->expect(fn () => GithubSponsors::user('Gummibeer')->isSponsoredBy('mattstauffer'))
    ->toBeTrue();

it('checks if someone is a sponsor of an organization')
    ->expect(fn () => GithubSponsors::organization('larabelles')->isSponsoredBy('Gummibeer'))
    ->toBeTrue();

it('checks if someone is a sponsor of a given login')
    ->expect(fn () => GithubSponsors::login('larabelles')->isSponsoredBy('Gummibeer'))
    ->toBeTrue();

it('checks if someone is not a sponsor')
    ->expect(fn () => GithubSponsors::viewer()->isSponsoredBy('Gummibeer'))
    ->toBeFalse();

it('checks if an organization is a sponsor')
    ->expect(fn () => GithubSponsors::viewer()->isSponsoredBy('spatie'))
    ->toBeTrue();

it('checks if given login has sponsoring enabled')
    ->expect(fn () => GithubSponsors::login('larabelles')->hasSponsoringEnabled())
    ->toBeTrue();

it('checks if given viewer has sponsoring enabled')
    ->expect(fn () => GithubSponsors::viewer()->hasSponsoringEnabled())
    ->toBeTrue();

it('checks if someone has sponsoring enabled')
    ->expect(fn () => GithubSponsors::user('Gummibeer')->hasSponsoringEnabled())
    ->toBeTrue();

it('checks if an organization has sponsoring enabled')
    ->expect(fn () => GithubSponsors::organization('larabelles')->hasSponsoringEnabled())
    ->toBeTrue();
