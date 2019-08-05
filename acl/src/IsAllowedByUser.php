<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetGroupIdsByUserId;
use RcmUser\User\Entity\UserInterface;

class IsAllowedByUser
{
    protected $isAllowedByUserId;

    public function __construct(
        IsAllowedByUserId $isAllowedByUserId
    ) {
        $this->isAllowedByUserId = $isAllowedByUserId;
    }

    /**
     * Returns true if the given user has access to the given action and
     * security properties.
     *
     * @param string $action
     * @param array $properties
     * @param UserInterface|null $user
     * @return bool
     */
    public function __invoke(string $action, array $properties, $user): bool
    {
        return $this->isAllowedByUserId->__invoke(
            $action,
            $properties,
            ($user instanceof UserInterface ? $user->getId() : null)
        );
    }
}
