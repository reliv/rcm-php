<?php
/**
 * UserDataPreparerTest.php
 *
 * UserDataPreparerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\User\Data;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\User\Data\UserDataPreparer;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * Class UserDataPreparerTest
 *
 * UserDataPreparerTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Data
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Data\UserDataPreparer
 */
class UserDataPreparerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * setup
     *
     * @return void
     */
    public function setup()
    {
        $this->userDataPreparer = new UserDataPreparer();

        $this->requestUser = new User('123');
        $this->requestUser->setUsername('testuser');
        $this->responseUser = new User();
        $this->responseUser->populate($this->requestUser);
        $this->existingUser = new User();
        $this->existingUser->populate($this->requestUser);
    }

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $result = $this->userDataPreparer->prepareUserCreate(
            $this->requestUser,
            $this->responseUser
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $this->assertEquals(
            $this->responseUser,
            $result->getData()
        );

        $result = $this->userDataPreparer->prepareUserUpdate(
            $this->requestUser,
            $this->responseUser,
            $this->existingUser
        );

        $this->assertInstanceOf(
            \RcmUser\Result::class,
            $result
        );

        $this->assertEquals(
            $this->responseUser,
            $result->getData()
        );
    }
}
