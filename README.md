# Laravel GitHub Sponsors

[![MIT License](https://img.shields.io/github/license/Astrotomic/laravel-github-sponsors.svg?label=License&color=blue&style=for-the-badge)](https://github.com/Astrotomic/laravel-github-sponsors/blob/main/LICENSE.md)
[![Latest Version](http://img.shields.io/packagist/v/astrotomic/laravel-github-sponsors.svg?label=Release&style=for-the-badge)](https://packagist.org/packages/astrotomic/laravel-github-sponsors)
[![PHP](https://img.shields.io/packagist/php-v/astrotomic/laravel-github-sponsors?color=%238892BE&style=for-the-badge)](https://github.com/Astrotomic/laravel-github-sponsors/blob/main/composer.json)

[![Ecologi](https://img.shields.io/ecologi/trees/astrotomic?color=green&label=Treeware&style=for-the-badge)](https://forest.astrotomic.info)
[![Larabelles](https://img.shields.io/badge/Larabelles-%F0%9F%A6%84-lightpink?style=for-the-badge)](https://larabelles.com)
[![opendor.me](https://img.shields.io/badge/opendor.me-%F0%9F%9A%80-ff4297?style=for-the-badge)](https://opendor.me)

[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/laravel-github-sponsors/pest.yml?branch=main&style=flat-square&logoColor=white&logo=github&label=Tests)](https://github.com/Astrotomic/laravel-github-sponsors/actions?query=workflow%3Apest)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/Astrotomic/laravel-github-sponsors/phpcs.yml?branch=main&style=flat-square&logoColor=white&logo=github&label=PHP+CS)](https://github.com/Astrotomic/laravel-github-sponsors/actions?query=workflow%3Aphpcs)
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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/Astrotomic/.github/blob/master/CONTRIBUTING.md) for details. You could also be interested in [CODE OF CONDUCT](https://github.com/Astrotomic/.github/blob/master/CODE_OF_CONDUCT.md).

### Security

If you discover any security related issues, please check [SECURITY](https://github.com/Astrotomic/.github/blob/master/SECURITY.md) for steps to report it.

## Credits

- [Tom Witkowski](https://github.com/Gummibeer)
- [Mert Aşan](https://github.com/mertasan)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

You're free to use this package, but if it makes it to your production environment I would highly appreciate you buying the world a tree.

It’s now common knowledge that one of the best tools to tackle the climate crisis and keep our temperatures from rising above 1.5C is to [plant trees](https://www.bbc.co.uk/news/science-environment-48870920). If you contribute to my forest you’ll be creating employment for local families and restoring wildlife habitats.

You can buy trees at [ecologi.com/astrotomic](https://forest.astrotomic.info)

Read more about Treeware at [treeware.earth](https://treeware.earth)
