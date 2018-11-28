<?php

namespace Rcm\ImmutableHistory\User;

interface UserIdToUserFullNameInterface
{
    public function __invoke(string $userId): string;
}
