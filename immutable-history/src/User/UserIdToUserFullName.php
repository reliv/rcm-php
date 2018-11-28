<?php

namespace Rcm\ImmutableHistory\User;

/**
 * This class provides default behavor. Over ride this class/service in your app outside RCM to lookup
 * user ids from your system
 *
 * Class UserIdToUserFullName
 * @package Rcm\ImmutableHistory\User
 */
class UserIdToUserFullName implements UserIdToUserFullNameInterface
{
    public function __invoke(string $userId): string
    {
        return '_NOT_IMPLEMENTED_';
    }
}
