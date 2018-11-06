<?php

namespace Rcm\ImmutableHistory\User;

class UserIdToUserFullName
{
    public function __invoke(string $siteId): string
    {
        //In the future this can be implmented to look up the actual user full name. Some kind of caching will be needed
        return 'USER_FULL_NAME_UNKNOWN';
    }
}
