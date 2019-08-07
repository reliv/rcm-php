<?php

namespace Rcm\Acl\Service;

use Rcm\Acl\Effects;
use Rcm\Acl\Entity\Group;
use Rcm\Acl\Query;
use Rcm\Acl\QueryResult;

class GroupsAndQueryToQueryResult // AKA "internal isAllowed", AKA "internal QueryRunner"
{
    protected $groupPoliciesAndQueryToApplicableRules;
    protected $rulesAndQueryToResult;

    public function __construct(
        GroupsAndQueryToApplicableRules $groupPoliciesAndQueryToApplicableRules,
        RulesAndQueryToResult $rulesAndQueryToResult
    ) {
        $this->groupPoliciesAndQueryToApplicableRules = $groupPoliciesAndQueryToApplicableRules;
        $this->rulesAndQueryToResult = $rulesAndQueryToResult;
    }

    /**
     * @param Group[] $allGroups
     * @param Query $query
     * @return QueryResult
     */
    public function __invoke(array $allGroups, Query $query): QueryResult
    {
        return $this->rulesAndQueryToResult->__invoke(
            $this->groupPoliciesAndQueryToApplicableRules->__invoke($allGroups, $query),
            $query
        );
    }
}
