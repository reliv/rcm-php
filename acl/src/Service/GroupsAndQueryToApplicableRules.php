<?php

namespace Rcm\Acl\Service;

use Rcm\Acl\Entity\Group;
use Rcm\Acl\Query;

class GroupsAndQueryToApplicableRules
{

    /**
     * @param Group[] $groupPolicies
     * @param Query $query
     * @return Rule[]
     */
    public function __invoke(array $groupPolicies, Query $query): array
    {
        // Filter out policies that are not for the groups indicated in the query

        $applicablePolicies = array_filter($groupPolicies, function (Group $policy) use ($query) {
            return in_array($policy->getId(), $query->getGroupIds());
        });

        // Concat the rules of all the applicable policies and return the rules
        return array_reduce(
            $applicablePolicies,
            function ($accumulator, Group $policy) {
                return array_merge($accumulator, $policy->getRules());
            },
            []
        );
    }
}
