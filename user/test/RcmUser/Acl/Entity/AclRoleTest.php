<?php
/**
 * AclRoleTest.php
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

use RcmUser\Acl\Entity\AclRole;
use RcmUser\Exception\RcmUserException;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AclRoleTest
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
 * @covers    \RcmUser\Acl\Entity\AclRole
 */
class AclRoleTest extends Zf2TestCase
{

    /**
     * testSetGet
     *
     * @covers \RcmUser\Acl\Entity\AclRole
     *
     * @return void
     */
    public function testSetGet()
    {
        $aclRole = new AclRole();
        $parentAclRole = new AclRole('ppp');
        $parentAclRole->setRoleId('ppp');

        $role = 'testrole';
        $prole = 'parenttestrole';
        $desc = 'Descript';
        $aclRole->setRoleId($role);
        $aclRole->setParentRoleId($prole);
        $aclRole->setDescription($desc);

        $this->assertTrue(
            $aclRole->getRoleId() === $role,
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $aclRole->getRoleId() === $role,
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $aclRole->getParentRoleId() === $prole,
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $aclRole->getParent() === $prole,
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $aclRole->getDescription() === $desc,
            'Setter or getter failed.'
        );

        $aclRole->setParentRole($parentAclRole);
        $this->assertTrue(
            $aclRole->getParent() === $parentAclRole,
            'Setter or getter failed.'
        );
    }

    /**
     * testSetRoleIdInValid
     *
     * @return void
     */
    public function testSetRoleIdInValid()
    {
        $aclRole = new AclRole();
        $roleId = '!inV@lid';

        try {
            $aclRole->setRoleId($roleId);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testSetRoleIdEmpty
     *
     * @return void
     */
    public function testSetRoleIdEmpty()
    {
        $aclRole = new AclRole();
        $roleId = '';

        try {
            $aclRole->setRoleId($roleId);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testSetParentRoleIdInValid
     *
     * @return void
     */
    public function testSetParentRoleIdInValid()
    {
        $aclRole = new AclRole();
        $roleId = '!inV@lid';

        try {
            $aclRole->setParentRoleId($roleId);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testSetParentRoleId
     *
     * @return void
     */
    public function testSetParentRoleId()
    {
        $aclRole = new AclRole();
        $parentAclRole = new AclRole();
        $parentAclRole->setRoleId('2222');
        $aclRole->setParentRoleId('');

        $this->assertNull(
            $aclRole->getParentRoleId(),
            'Role id that is set empty should be null'
        );

        $aclRole->setParentRole($parentAclRole);

        $this->assertEquals(
            $parentAclRole->getRoleId(),
            $aclRole->getParentRoleId(),
            'Parent role id not populated.'
        );

        $aclRole->setParentRoleId('3333');

        $this->assertNull(
            $aclRole->getParentRole(),
            'New parent id should clear parent role object'
        );
    }

    /**
     * testPopulate
     *
     * @covers \RcmUser\Acl\Entity\AclRole::populate
     *
     * @return void
     */
    public function testPopulate()
    {
        $aclRole = new AclRole();
        $parentAclRole = new AclRole();
        $parentAclRole->setRoleId('ppp');
        $aclRoleA = [
            'roleId' => 'arrayrolea',
            'parentRoleId' => 'ppp',
            'description' => 'arrayRoleA',
            'parentRole' => $parentAclRole
        ];
        $aclRoleB = new AclRole();
        $parentAclRoleB = new AclRole();
        $parentAclRoleB->setRoleId('pppb');
        $aclRoleB->setRoleId('roleb');
        $aclRoleB->setParentRoleId('pppb');
        $aclRoleB->setDescription('roleb');
        $aclRoleB->setParentRole($parentAclRoleB);

        $aclRoleC = 'wrong format';

        $aclRole->populate($aclRoleA);

        $this->assertTrue(
            $aclRole->getRoleId() === 'arrayrolea',
            'Setter or getter failed.'
        );

        $aclRole->populate($aclRoleB);

        $this->assertTrue(
            $aclRole->getRoleId() === 'roleb',
            'Setter or getter failed.'
        );

        try {
            $aclRole->populate($aclRoleC);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testJsonSerialize
     *
     * @covers \RcmUser\Acl\Entity\AclRole::jsonSerialize
     *
     * @return void
     */
    public function testJsonSerialize()
    {
        $aclRole = new AclRole();
        $aclRole->setRoleId('role');
        $aclRole->setParentRoleId('role');
        $aclRole->setDescription('role');

        $aclRoleJson = json_encode($aclRole);

        $this->assertJson($aclRoleJson, 'User not converted to JSON.');
    }

    /**
     * testArrayIterator
     *
     * @covers \RcmUser\Acl\Entity\AclRole::getIterator
     *
     * @return void
     */
    public function testArrayIterator()
    {
        $aclRole = new AclRole();
        $aclRole->setRoleId('role');
        $aclRole->setParentRoleId('role');
        $aclRole->setDescription('role');

        $iter = $aclRole->getIterator();
        $aclRoleArr = iterator_to_array($aclRole);
        $aclRoleArr2 = iterator_to_array($iter);

        $this->assertTrue($aclRoleArr == $aclRoleArr2, 'Iterator failed work.');

        $this->assertTrue(is_array($aclRoleArr), 'Iterator failed work.');

        $this->assertArrayHasKey(
            'roleId',
            $aclRoleArr,
            'Iterator did not populate correctly.'
        );
    }

    /**
     * testToString
     *
     * @return void
     */
    public function testToString()
    {
        $aclRole = new AclRole();
        $aclRole->setRoleId('role');

        $this->assertEquals(
            'role',
            $aclRole->__toString(),
            'toString should return role id.'
        );
    }
}
