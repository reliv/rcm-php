<?php

/**
 * UserRolePropertyTest.php
 *
 * UserRolePropertyTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace RcmUser\Test\User\Entity;

use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserRoleProperty;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserRolePropertyTest
 *
 * UserRolePropertyTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Entity\UserRoleProperty
 */
class UserRolePropertyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $roles = ['TestRole1', 'TestRole2'];
        $userRoleProperty = new UserRoleProperty($roles);

        $this->assertTrue(
            $userRoleProperty->hasRoles()
        );

        $this->assertEquals(
            $roles,
            $userRoleProperty->getRoles()
        );

        $userRoleProperty->setRole('TestRole3');

        $this->assertTrue(
            $userRoleProperty->hasRole('TestRole3')
        );

        $this->assertEquals(
            'TestRole3',
            $userRoleProperty->getRole('TestRole3')
        );

        $this->assertJson(
            json_encode($userRoleProperty)
        );

        $userRoleProperty2 = new UserRoleProperty($roles);
        $userRoleProperty2->populate($userRoleProperty);
        $this->assertTrue(
            $userRoleProperty2->hasRole('TestRole2')
        );

        $userRoleProperty3 = new UserRoleProperty($roles);
        $userRoleProperty3->populate($roles);
        $this->assertTrue(
            $userRoleProperty3->hasRole('TestRole2')
        );

        try {
            $userRoleProperty3->populate('NOPE');
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }
}
