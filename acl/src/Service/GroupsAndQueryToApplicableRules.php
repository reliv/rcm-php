<?php

namespace Rcm\Acl\Service;

use Rcm\Acl\Entity\Group;
use Rcm\Acl\Query;

class GroupsAndQueryToApplicableRules
{

    /**
     * @param Group[] $groups
     * @param Query $query
     * @return Rule[]
     */
    public function __invoke(array $groups, Query $query): array
    {
        // Filter out groups that are not for the groups indicated in the query
        $applicableGroups = array_filter($groups, function (Group $group) use ($query) {

            return in_array($group->getName(), $query->getGroupNames());
        });

        // Concat the rules of all the applicable Groups and return the rules
        $applicableRules= array_reduce(
            $applicableGroups,
            function ($accumulator, Group $group) {
                return array_merge($accumulator, $group->getRules());
            },
            []
        );

        return $applicableRules;
    }
}
