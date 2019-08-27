<?php

namespace Rcm\AclTest;

use PHPUnit\Framework\TestCase;
use Rcm\Acl\Effects;
use Rcm\Acl\GroupsAndQueryToApplicableRules;
use Rcm\Acl\Entity\Group;
use Rcm\Acl\Query;
use Rcm\Acl\Rule;
use Mockery as M;

class GroupPoliciesAndQueryToApplicableRulesTest extends TestCase
{
    /**
     * @param Rule[] $array
     * @param Rule $rule
     * @return bool
     */
    protected static function arrayContainsRule(array $array, Rule $rule): bool
    {
        foreach ($array as $arrayRule) {
            if ($arrayRule->getEffect() === $rule->getEffect()
                && $arrayRule->getProperties() === $rule->getProperties()
                && $arrayRule->getActions() === $rule->getActions()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Rule[] $array
     * @param Rule[] $onlyRules
     * @return bool
     */
    protected static function arrayContainsOnlyRules(array $array, array $onlyRules): bool
    {
        if (count($array) !== count($onlyRules)) {
            return false;
        }
        foreach ($onlyRules as $onlyRule) {
            if (!self::arrayContainsRule($array, $onlyRule)) {
                return false;
            }
        }

        return true;
    }

    protected static function createMockGroup($id, $rules)
    {
        $group = M::mock(Group::class);
        $group->allows()->getName()->andReturns($id);
        $group->allows()->getRules()->andReturns($rules);

        return $group;
    }

    public function testItAllowsOnlyAndAllOfTheRulesThroughThatMatchTheGroupNamesInQuery()
    {
        $auRule1 = new Rule(Effects::ALLOW, ['write1'], ['local' => 'en_AU', 'type' => 'pageA']);
        $auRule2 = new Rule(Effects::ALLOW, ['write2'], ['local' => 'en_AU', 'type' => 'pageB']);
        $deRule1 = new Rule(Effects::ALLOW, ['write3'], ['local' => 'de_DE', 'type' => 'pageC']);
        $deRule2 = new Rule(Effects::ALLOW, ['write4'], ['local' => 'de_DE', 'type' => 'pageD']);
        $deRule3 = new Rule(Effects::ALLOW, ['write5'], ['local' => 'de_DE', 'type' => 'pageE']);
        $phRule1 = new Rule(Effects::ALLOW, ['write6'], ['local' => 'ph_PH', 'type' => 'pageF']);
        $caRule1 = new Rule(Effects::ALLOW, ['write7'], ['local' => 'en_CA', 'type' => 'pageG']);

        $groupPolices = [
            self::createMockGroup(44, [$auRule1, $auRule2]),
            self::createMockGroup(55, [$deRule1, $deRule2, $deRule3]),
            self::createMockGroup(66, [$phRule1]),
            self::createMockGroup(77, [$caRule1]),
        ];

        $query1 = new Query(
            'read',
            [44, 66],
            ['locale' => 'en_EN', 'type' => 'page']
        );
        $unit = new \Rcm\Acl\Service\GroupsAndQueryToApplicableRules();
        self::assertTrue(self::arrayContainsOnlyRules(
            $unit->__invoke($groupPolices, $query1),
            [$auRule1, $auRule2, $phRule1]
        ));
        self::assertTrue(self::arrayContainsOnlyRules(
            $unit->__invoke(array_reverse($groupPolices), $query1),
            [$auRule1, $auRule2, $phRule1]
        ));

        $query2 = new Query(
            'read',
            [55, 77],
            ['locale' => 'en_EN', 'type' => 'page']
        );
        $unit = new \Rcm\Acl\Service\GroupsAndQueryToApplicableRules();
        self::assertTrue(self::arrayContainsOnlyRules(
            $unit->__invoke($groupPolices, $query2),
            [$deRule1, $deRule2, $deRule3, $caRule1]
        ));
        self::assertTrue(self::arrayContainsOnlyRules(
            $unit->__invoke(array_reverse($groupPolices), $query2),
            [$deRule1, $deRule2, $deRule3, $caRule1]
        ));
    }
}
