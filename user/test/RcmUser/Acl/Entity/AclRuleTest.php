<?php
/**
 * AclRuleTest.php
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Entity;

use RcmUser\Acl\Entity\AclRule;
use RcmUser\Exception\RcmUserException;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AclRuleTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Entity\AclRule
 */
class AclRuleTest extends Zf2TestCase
{
    /**
     * testSetGet
     *
     * @covers \RcmUser\Acl\Entity\AclRule
     *
     * @return void
     */
    public function testSetGet()
    {
        $aclRule = new AclRule();
        $rule = 'allow';
        $roleId = 'somerole';
        $resource = 'someresource';
        $privileges = ['someprivilege'];
        $assertion = 'someassertion';

        $aclRule->setRule($rule);
        $aclRule->setRoleId($roleId);
        $aclRule->setResourceId($resource);
        $aclRule->setPrivileges($privileges);
        $aclRule->setAssertion($assertion);

        $this->assertEquals(
            $rule,
            $aclRule->getRule(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $roleId,
            $aclRule->getRoleId(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $resource,
            $aclRule->getResourceId(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $privileges,
            $aclRule->getPrivileges(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $assertion,
            $aclRule->getAssertion(),
            'Setter or getter failed.'
        );

        $aclRule->setPrivileges([]);
        $this->assertEquals(
            [],
            $aclRule->getPrivileges(),
            'Empty privileges should be stored as [].'
        );
    }

    /**
     * testSetRule
     *
     * @return void
     */
    public function testSetRule()
    {
        $aclRule = new AclRule();
        $rule = 'NOPE';

        try {
            $aclRule->setRule($rule);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(
                \RcmUser\Exception\RcmUserException::class,
                $e
            );

            return;
        }

        $this->fail("Expected exception not thrown");
    }

    public function testPopulate()
    {
        $aclRuleA = new AclRule();
        $aclRuleB = new AclRule();
        $data = [
            'rule' => 'allow',
            'roleId' => 'somerole',
            'resourceId' => 'someresource',
            // NOTE privilege is deprecated
            'privilege' => null,
            'privileges' => ['someprivilege'],
            'assertion' => 'someassertion',
        ];

        $aclRuleA->populate($data);

        $arrayA = iterator_to_array($aclRuleA);

        $this->assertEquals($data, $arrayA, 'Populate failed.');

        $aclRuleB->populate($aclRuleA);

        $arrayB = iterator_to_array($aclRuleA);

        $this->assertEquals($arrayA, $arrayB, 'Populate failed.');

        try {
            $aclRuleB->populate('NOPE');
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(
                \RcmUser\Exception\RcmUserException::class,
                $e
            );

            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testJsonSerialize
     *
     * @covers \RcmUser\Acl\Entity\AclRule::jsonSerialize
     *
     * @return void
     */
    public function testJsonSerialize()
    {
        $aclRule = new AclRule();
        $rule = 'allow';
        $roleId = 'role';
        $resource = 'someresource';
        $privileges = ['someprivilege'];

        $aclRule->setRule($rule);
        $aclRule->setRoleId($roleId);
        $aclRule->setResourceId($resource);
        $aclRule->setPrivileges($privileges);

        $aclRuleJson = json_encode($aclRule);

        $this->assertJson($aclRuleJson, 'User not converted to JSON.');
    }

    /**
     * testArrayIterator
     *
     * @covers \RcmUser\Acl\Entity\AclRule::getIterator
     *
     * @return void
     */
    public function testArrayIterator()
    {
        $aclRule = new AclRule();
        $rule = 'allow';
        $roleId = 'role';
        $resource = 'someresource';
        $privileges = ['someprivilege'];

        $aclRule->setRule($rule);
        $aclRule->setRoleId($roleId);
        $aclRule->setResourceId($resource);
        $aclRule->setPrivileges($privileges);

        $iter = $aclRule->getIterator();
        $array1 = iterator_to_array($aclRule);
        $array2 = iterator_to_array($iter);

        $this->assertTrue($array1 == $array2, 'Iterator failed work.');

        $this->assertTrue(is_array($array1), 'Iterator failed work.');

        $this->assertArrayHasKey(
            'rule',
            $array1,
            'Iterator did not populate correctly.'
        );
    }
}
