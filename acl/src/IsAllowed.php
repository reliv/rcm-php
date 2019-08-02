<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetGroupIdsByUserId;

/**
 * Class IsAllowed
 * @package Rcm\Acl
 */
class IsAllowed
{
    protected $runQuery;
    protected $getGroupsByUserId;
    protected $getCurrentUserId;

    public function __construct(
        RunQuery $runQuery,
        GetCurrentUserId $getCurrentUserId,
        GetGroupIdsByUserId $getGroupsByUserId
    ) {
        $this->runQuery = $runQuery;
        $this->getGroupsByUserId = $getGroupsByUserId;
        $this->getCurrentUserId = $getCurrentUserId;
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
        $queryWithGroups = new Query(
            $action,
            $this->getGroupsByUserId->__invoke($this->getCurrentUserId->__invoke()),
            $properties
        );

        return $this->runQuery->__invoke($queryWithGroups)->getEffect() === Effects::ALLOW;
    }
}
