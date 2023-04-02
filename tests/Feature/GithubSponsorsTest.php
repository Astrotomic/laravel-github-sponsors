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

it('checks if someone is a sponsor of authenticated user', function () {
    expect($firstSponsor = GithubSponsors::viewer()->sponsors()->first())
        ->toBeArray()
        ->not->toBeEmpty()
             ->and(GithubSponsors::viewer()->isSponsoredBy($firstSponsor['login']))
             ->toBeTrue();
});

it('checks if someone is a sponsor of an user')
    ->expect(fn () => GithubSponsors::user('Gummibeer')->isSponsoredBy('mertasan'))
    ->toBeTrue();

it('checks if someone is a sponsor of an organization')
    ->expect(fn () => GithubSponsors::organization('larabelles')->isSponsoredBy('taylorotwell'))
    ->toBeTrue();

it('checks if someone is a sponsor of a given login', function () {
    expect($firstSponsor = GithubSponsors::user('Gummibeer')->sponsors()->first())
        ->toBeArray()
        ->not->toBeEmpty()
             ->and(GithubSponsors::login('Gummibeer')->isSponsoredBy($firstSponsor['login']))
             ->toBeTrue();
});

it('checks if someone is not a sponsor')
    ->expect(fn () => GithubSponsors::viewer()->isSponsoredBy('Gummibeer'))
    ->toBeFalse();
