# Laravel GitHub Sponsors

[![Latest Version](http://img.shields.io/packagist/v/astrotomic/laravel-github-sponsors.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/laravel-github-sponsors)
[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-github-sponsors.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-github-sponsors/blob/master/LICENSE)
[![Offset Earth](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-green?style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)

[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-github-sponsors/pest?style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-github-sponsors/actions?query=workflow%3Apest)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/Astrotomic/laravel-github-sponsors/phpcs?style=flat-square&logoColor=white&logo=github&label=PHP+CS)](https://github.com/Astrotomic/laravel-github-sponsors/actions?query=workflow%3Aphpcs)
[![Total Downloads](https://img.shields.io/packagist/dt/astrotomic/laravel-github-sponsors.svg?label=Downloads&style=flat-square)](https://packagist.org/packages/astrotomic/laravel-github-sponsors)

Retrieve the GitHub Sponsors of any user/organization and check if someone is sponsoring you.

## Installation

```bash
composer require astrotomic/laravel-github-sponsors
```

## Configuration

Set `services.github.sponsors_token` config value or override service binding in your own service provider.
The used PAT needs at least `read:user` and `read:org` permissions to retrieve all sponsors.

```php
$this->app->when(\Astrotomic\GithubSponsors\Graphql::class)
    ->needs('$token')
    ->give('my_custom_secret');
```

## Usage

```php
use Astrotomic\GithubSponsors\Facades\GithubSponsors;

// all sponsors for current authenticated user
GithubSponsors::viewer()->sponsors();
// all sponsors for given name without knowing what it is
GithubSponsors::login('larabelles')->sponsors();
// all sponsors for given user
GithubSponsors::user('Gummibeer')->sponsors();
// all sponsors for given organization
GithubSponsors::organization('Astrotomic')->sponsors();

// select specific attributes
GithubSponsors::viewer()->sponsors(['login', 'name', 'avatarUrl']);
// select specific attributes and company only for users
GithubSponsors::viewer()->sponsors(['login', 'name', 'avatarUrl'], ['company']);
// select specific attributes and email only for organizations
GithubSponsors::viewer()->sponsors(['login', 'name', 'avatarUrl'], [], ['email']);

// check if viewer sponsored by Gummibeer
GithubSponsors::viewer()->isSponsoredBy('Gummibeer');
// check if viewer sponsors Gummibeer
GithubSponsors::viewer()->isSponsoring('Gummibeer');
// check if viewer has sponsors
GithubSponsors::viewer()->hasSponsors();
// check how many sponsors the viewer has
GithubSponsors::viewer()->sponsorsCount();
```
