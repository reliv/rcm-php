<?php

namespace Rcm\ImmutableHistory\User;

class UserIdToUserFullName
{
    public function __invoke(string $siteId): string
    {
        //@TODO implement and return actual domain name and do per-request caching
        return 'USER_FULL_NAME_UNKNOWN';
    }
}
