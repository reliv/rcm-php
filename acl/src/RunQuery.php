<?php

namespace Rcm\Acl;

use Rcm\Acl\Service\GetAllGroups;
use Rcm\Acl\Service\GroupsAndQueryToQueryResult;

class RunQuery
{
    protected $groupsAndQueryToQueryResult;
    protected $getAllGroups;

    public function __construct(
        GetAllGroups $getAllGroups,
        GroupsAndQueryToQueryResult $groupsAndQueryToQueryResult
    ) {
        $this->groupsAndQueryToQueryResult = $groupsAndQueryToQueryResult;
        $this->getAllGroups = $getAllGroups;
    }

    public function __invoke(Query $query): QueryResult
    {
        return $this->groupsAndQueryToQueryResult->__invoke(
            $this->getAllGroups->__invoke(),
            $query
        );
    }
}
