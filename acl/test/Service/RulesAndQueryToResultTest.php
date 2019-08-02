<?php

namespace Rcm\AclTest;

use PHPUnit\Framework\TestCase;
use Rcm\Acl\Effects;
use Rcm\Acl\Query;
use Rcm\Acl\Rule;
use Rcm\Acl\Service\RulesAndQueryToResult;

final class EmailTest extends TestCase
{
    public function testDeniesIfActionDoesNotMatch()
    {
        $rules = [
            new Rule(
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            )
        ];
        $query = new Query(
            'read',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::DENY);
    }

    public function testDeniesIfRuleHasPropThatIsNotInQuery()
    {
        $rules = [
            new Rule(
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
            )
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::DENY);
    }

    public function testDeniesIfRuleHasPropThatIsNotInQueryAndActionDoesNotMatch()
    {
        $rules = [
            new Rule(
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
            )
        ];
        $query = new Query(
            'read',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::DENY);
    }

    public function testAllowsIfQueryHasPropThatIsNotInRule()
    {
        $rules = [
            new Rule(
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            )
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::ALLOW);
    }

    public function testAllowsIfExactMatch()
    {
        $rules = [
            new Rule(
                Effects::DENY,
                ['read'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
            new Rule( //This is the match
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
            new Rule(
                Effects::DENY,
                ['write'],
                ['locale' => 'en_US', 'type' => 'page']
            ),
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::ALLOW);
        $this->assertEquals($unit->__invoke(array_reverse($rules), $query)->getEffect(), Effects::ALLOW);
    }

    public function testDeniesIfConflictingRulesOfEqualSpecificness()
    {
        $rules = [
            new Rule(
                Effects::DENY,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
            new Rule( //This is the match
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::DENY);
        $this->assertEquals($unit->__invoke(array_reverse($rules), $query)->getEffect(), Effects::DENY);
    }

    public function testAllowsIfHaveDenyRuleAndMoreSpecificAllowRule()
    {
        $rules = [
            new Rule(
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
            ),
            new Rule( //This is the match
                Effects::DENY,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::ALLOW);
        $this->assertEquals($unit->__invoke(array_reverse($rules), $query)->getEffect(), Effects::ALLOW);
    }

    // This test is missing from the NodeJS version.
    public function testDenysIfHaveAllowRuleAndMoreSpecificDenyRule()
    {
        $rules = [
            new Rule(
                Effects::DENY,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
            ),
            new Rule( //This is the match
                Effects::ALLOW,
                ['write'],
                ['locale' => 'fr_FR', 'type' => 'page']
            ),
        ];
        $query = new Query(
            'write',
            ['french_content_manager'],
            ['locale' => 'fr_FR', 'type' => 'page', 'flavor' => 'vanilla']
        );
        $unit = new RulesAndQueryToResult();
        $this->assertEquals($unit->__invoke($rules, $query)->getEffect(), Effects::DENY);
        $this->assertEquals($unit->__invoke(array_reverse($rules), $query)->getEffect(), Effects::DENY);
    }
}
