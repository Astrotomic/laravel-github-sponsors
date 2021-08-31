<?php

namespace Astrotomic\GithubSponsors\Contracts;

use Illuminate\Support\LazyCollection;

interface Client
{
    public function isSponsoredBy(string $sponsor): bool;

    public function isSponsoring(string $account): bool;

    public function sponsorsCount(): int;

    public function hasSponsors(): bool;

    public function sponsors(array $fields = ['login'], array $userFields = [], array $organizationFields = []): LazyCollection;
}