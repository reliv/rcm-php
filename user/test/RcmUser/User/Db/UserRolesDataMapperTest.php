<?php

namespace RcmUser\Test\User\Db;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Result;
use RcmUser\User\Db\UserRolesDataMapper;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * Class UserRolesDataMapperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Db\UserRolesDataMapper
 */
class UserRolesDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRolesDataMapper $userRolesDataMapper
     */
    public $userRolesDataMapper;

    /**
     * setup
     *
     * @return void
     */
    public function setup()
    {
        $this->roles = ['someroles'];

        $rolsResult = new Result($this->roles);

        $this->aclRoleDataMapper = $this->getMockBuilder(
            \RcmUser\Acl\Db\AclRoleDataMapperInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->aclRoleDataMapper->expects($this->any())
            ->method('fetchAll')
            ->will($this->returnValue($rolsResult));


        $this->userRolesDataMapper
            = new UserRolesDataMapper($this->aclRoleDataMapper);

        $this->user = new User('123');
        $this->user->setUsername('testuser');
    }

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $this->assertEquals(
            $this->aclRoleDataMapper,
            $this->userRolesDataMapper->getAclRoleDataMapper()
        );

        // this also re-gets to test the cache bit.
        $this->assertEquals(
            $this->roles,
            $this->userRolesDataMapper->getAvailableRoles()
        );
    }

    /**
     * testFetchAll
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchAll()
    {
        $result = $this->userRolesDataMapper->fetchAll(
            $this->user,
            'roleId'
        );
    }

    /**
     * testAdd
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testAdd()
    {
        $result = $this->userRolesDataMapper->add(
            $this->user,
            'roleId'
        );
    }

    /**
     * testAdd
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testRemove()
    {
        $result = $this->userRolesDataMapper->remove(
            $this->user,
            'roleId'
        );
    }

    /**
     * testCreate
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testCreate()
    {
        $result = $this->userRolesDataMapper->create(
            $this->user,
            $this->roles
        );
    }

    /**
     * testRead
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testRead()
    {
        $result = $this->userRolesDataMapper->read(
            $this->user
        );
    }

    /**
     * testUpdate
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testUpdate()
    {
        $result = $this->userRolesDataMapper->update(
            $this->user,
            $this->roles
        );
    }

    /**
     * testDelete
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testDelete()
    {
        $result = $this->userRolesDataMapper->delete(
            $this->user,
            $this->roles
        );
    }

    /**
     * testCan
     *
     * @return void
     */
    public function testCan()
    {
        $user = new User();
        $badRole = 'NOPE';

        $this->assertFalse(
            $this->userRolesDataMapper->canAdd(
                $user,
                $badRole
            )
        );

        $this->assertFalse(
            $this->userRolesDataMapper->canRemove(
                $user,
                $badRole
            )
        );

        $user->setId('123123');

        $this->assertFalse(
            $this->userRolesDataMapper->canAdd(
                $user,
                $badRole
            )
        );

        $this->assertTrue(
            $this->userRolesDataMapper->canAdd(
                $user,
                $this->roles[0]
            )
        );

        $this->assertTrue(
            $this->userRolesDataMapper->canRemove(
                $user,
                $this->roles[0]
            )
        );
    }
}
