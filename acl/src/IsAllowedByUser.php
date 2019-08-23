<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\GetGroupNamesByUserInterface;
use Rcm\Acl\Service\GroupNamesByUser;
use RcmUser\User\Entity\UserInterface;

class IsAllowedByUser
{
    protected $runQuery;
    protected $getGroupsByUser;

    public function __construct(
        RunQuery $runQuery,
        GetGroupNamesByUserInterface $getGroupsByUser
    ) {
        $this->runQuery = $runQuery;
        $this->getGroupsByUser = $getGroupsByUser;
    }

    /**
     * Returns true if the given user ID has access to the given action and
     * security properties.
     *
     * @param string $action
     * @param array $properties
     * @param UserInterface|null $user
     * @return bool
     */
    public function __invoke(string $action, array $properties, $user): bool
    {
        $queryWithGroups = new Query(
            $action,
            $this->getGroupsByUser->__invoke($user),
            $properties
        );
        return $this->runQuery->__invoke($queryWithGroups)->getEffect() === Effects::ALLOW;
    }
}
