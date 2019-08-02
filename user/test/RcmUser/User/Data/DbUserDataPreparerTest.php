<?php

namespace RcmUser\Test\User\Data;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\User\Data\DbUserDataPreparer;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * Class DbUserDataPreparerTest
 *
 * DbUserDataPreparerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Data\DbUserDataPreparer
 */
class DbUserDataPreparerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \RcmUser\User\Data\DbUserDataPreparer $dbUserDataPreparer
     */
    public $dbUserDataPreparer;

    /**
     * setup
     *
     * @return void
     */
    public function setup()
    {
        $this->hash = '#hash#';

        $this->encryptor = $this->getMockBuilder(
            '\Zend\Crypt\Password\PasswordInterface'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $this->encryptor->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->hash));

        $this->encryptor->expects($this->any())
            ->method('verify')
            ->will($this->returnValue(true));

        $this->dbUserDataPreparer = new DbUserDataPreparer();
        $this->dbUserDataPreparer->setEncryptor($this->encryptor);

        $this->requestUser = new User('123');
        $this->requestUser->setUsername('testuser');
        $this->requestUser->setPassword('testpass');
        $this->responseUser = new User();
        $this->responseUser->populate($this->requestUser);
        $this->existingUser = new User();
        $this->existingUser->populate($this->requestUser);
    }

    public function test()
    {

        $result = $this->dbUserDataPreparer->prepareUserCreate(
            $this->requestUser,
            $this->responseUser
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $user = $result->getData();

        $this->assertEquals(
            $this->hash,
            $user->getPassword()
        );

        $this->assertEquals(
            UserInterface::STATE_DISABLED,
            $user->getState()
        );

        $this->requestUser->setPassword('NEWPASS');
        $this->requestUser->setState('NEWSTATE');

        $result = $this->dbUserDataPreparer->prepareUserUpdate(
            $this->requestUser,
            $this->responseUser,
            $this->existingUser
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $user = $result->getData();

        $this->assertEquals(
            $this->hash,
            $user->getPassword()
        );

        $this->assertEquals(
            'NEWSTATE',
            $user->getState()
        );
    }
}
