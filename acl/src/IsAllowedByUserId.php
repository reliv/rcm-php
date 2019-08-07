<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GroupNamesByUser;

class IsAllowedByUserId
{
    protected $runQuery;
    protected $getGroupsByUserId;

    public function __construct(
        RunQuery $runQuery,
        GroupNamesByUser $getGroupsByUserId
    ) {
        $this->runQuery = $runQuery;
        $this->getGroupsByUserId = $getGroupsByUserId;
    }

    /**
     * Returns true if the given user ID has access to the given action and
     * security properties.
     *
     * @param string $action
     * @param array $properties
     * @param string|null $userId
     * @return bool
     */
    public function __invoke(string $action, array $properties, $userId): bool
    {
        $queryWithGroups = new Query(
            $action,
            $this->getGroupsByUserId->__invoke($userId),
            $properties
        );

        return $this->runQuery->__invoke($queryWithGroups)->getEffect() === Effects::ALLOW;
    }
}
