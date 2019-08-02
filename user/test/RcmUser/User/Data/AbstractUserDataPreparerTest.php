<?php

namespace RcmUser\Test\User\Data;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\User\Data\AbstractUserDataPreparer;
use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * Class AbstractUserDataPreparerTest
 *
 * AbstractUserDataPreparerTest
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
 * @covers    \RcmUser\User\Data\AbstractUserDataPreparer
 */
class AbstractUserDataPreparerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * setup
     *
     * @return void
     */
    public function setup()
    {
        $this->abstractUserDataPreparer = new AbstractUserDataPreparer();

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
        $result = $this->abstractUserDataPreparer->prepareUserCreate(
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

        $result = $this->abstractUserDataPreparer->prepareUserUpdate(
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
