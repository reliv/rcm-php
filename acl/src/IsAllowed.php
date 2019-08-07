<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GroupNamesByUser;

/**
 * Class IsAllowed
 * @package Rcm\Acl
 */
class IsAllowed
{
    protected $getCurrentUser;
    protected $isAllowedByUser;

    public function __construct(
        GetCurrentUser $getCurrentUser,
        IsAllowedByUser $isAllowedByUser
    ) {
        $this->getCurrentUser = $getCurrentUser;
        $this->isAllowedByUser = $isAllowedByUser;
    }

    /**
     * Returns true if the CURRENT user/request has access to the given action and
     * security properties.
     *
     * @param string $action
     * @param array $properties
     * @return bool
     * @throws \Exception
     */
    public function __invoke(string $action, array $properties): bool
    {
        return $this->isAllowedByUser->__invoke($action, $properties, $this->getCurrentUser->__invoke());
    }
}
