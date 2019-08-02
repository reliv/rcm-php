<?php

namespace RcmUser\Test\User\Db;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\User\Db\UserDataMapper;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * Class UserDataMapperTest
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
 * @covers    \RcmUser\User\Db\UserDataMapper
 */
class UserDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserDataMapper $userDataMapper
     */
    public $userDataMapper;

    /**
     * setup
     *
     * @return void
     */
    public function setup()
    {
        $this->userDataPreparer = $this->getMockBuilder(
            \RcmUser\User\Data\UserDataPreparerInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userValidator = $this->getMockBuilder(
            \RcmUser\User\Data\UserValidatorInterface::class
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->userDataMapper = new UserDataMapper();

        $this->requestUser = new User('123');
        $this->requestUser->setUsername('testuser');
        $this->responseUser = new User();
        $this->responseUser->populate($this->requestUser);
        $this->existingUser = new User();
        $this->existingUser->populate($this->requestUser);
    }

    /**
     * testcanUpdate
     *
     * @return void
     */
    public function test()
    {
        $this->userDataMapper = new UserDataMapper();

        $this->userDataMapper->setUserDataPreparer($this->userDataPreparer);

        $this->assertEquals(
            $this->userDataPreparer,
            $this->userDataMapper->getUserDataPreparer()
        );

        $this->userDataMapper->setUserValidator($this->userValidator);

        $this->assertEquals(
            $this->userValidator,
            $this->userDataMapper->getUserValidator()
        );

        $user = new User();
        $this->assertFalse(
            $this->userDataMapper->canUpdate($user)
        );

        $this->assertFalse(
            $this->userDataMapper->canDelete($user)
        );

        $user->setId('234');

        $this->assertTrue(
            $this->userDataMapper->canUpdate($user)
        );

        $this->assertTrue(
            $this->userDataMapper->canDelete($user)
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
        $result = $this->userDataMapper->fetchAll();
    }

    /**
     * testFetchById
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchById()
    {
        $result = $this->userDataMapper->fetchById('userId');
    }

    /**
     * testFetchByUsername
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByUsername()
    {
        $result = $this->userDataMapper->fetchByUsername('username');
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
        $result = $this->userDataMapper->create(
            $this->requestUser,
            $this->responseUser
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
        $result = $this->userDataMapper->read(
            $this->requestUser,
            $this->responseUser
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
        $result = $this->userDataMapper->update(
            $this->requestUser,
            $this->responseUser,
            $this->existingUser
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
        $result = $this->userDataMapper->delete(
            $this->requestUser,
            $this->responseUser
        );
    }
}
