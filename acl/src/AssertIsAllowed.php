<?php

namespace Rcm\Acl;

use Rcm\Acl\Exception\NotAllowedByQueryRunException;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GroupNamesByUser;

/**
 * Class IsAllowed
 * @package Rcm\Acl
 */
class AssertIsAllowed
{
    protected $isAllowed;

    public function __construct(
        IsAllowed $isAllowed
    ) {
        $this->isAllowed = $isAllowed;
    }

    /**
     * Throws an exception if the CURRENT user/request does NOT have access to the
     * given action and security properties.
     *
     * @param string $action
     * @param array $properties
     * @throws \Exception
     */
    public function __invoke(string $action, array $properties)
    {
        if (!$this->isAllowed->__invoke($action, $properties)) {
            throw new NotAllowedByQueryRunException(
                'An ACL query ran and found the following to be not allowed: ' . json_encode([
                    'action' => $action,
                    'properties' => $properties
                ])
            );
        }
    }
}
