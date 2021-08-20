# Laravel GitHub Sponsors

Retrieve the GitHub Sponsors of a given user/organization.

## Installation

```bash
composer require astrotomic/laravel-github-sponsors
```

## Configuration

Set `services.github.sponsors_token` config value or override service binding in your own service provider.
The used PAT needs at least `read:org` permissions to retrieve sponsoring organizations.

```php
use Astrotomic\GithubSponsors\GithubSponsors;
use Illuminate\Contracts\Container\Container;

$this->app->bind(GithubSponsors::class, function(Container $app): GithubSponsors {
    return new GithubSponsors(
        $app->make('config')->get('your.own.config.key')
    );
});
```

## Usage

```php
use Astrotomic\GithubSponsors\Facades\GithubSponsors;

GithubSponsors::fromViewer()->all(); // all sponsors for current authenticated user
GithubSponsors::fromUser('Gummibeer')->all(); // all sponsors for given user
GithubSponsors::fromOrganization('Astrotomic')->all(); // all sponsors for given organization

GithubSponsors::fromViewer()->cursor(); // lazy collection - using less memory

GithubSponsors::fromViewer()->select('login', 'name', 'avatarUrl')->all(); // select specific attributes

GithubSponsors::fromViewer()->isSponsor('Gummibeer'); // check if someone is a sponsor
```
