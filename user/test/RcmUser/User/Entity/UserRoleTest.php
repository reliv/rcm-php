<?php
/**
 * UserRoleTest.php
 *
 * TEST
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
use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Entity\UserRole;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserRoleTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\User\Entity\UserRole
 */
class UserRoleTest extends Zf2TestCase
{

    /**
     * testSetGet
     *
     * @covers \RcmUser\User\Entity\UserRole
     *
     * @return void
     */
    public function testSetGet()
    {
        $userRole = new UserRole();

        $id = 123;
        $roleId = 234;
        $userId = '456';

        $userRole->setId($id);
        $userRole->setRoleId($roleId);
        $userRole->setUserId($userId);

        $this->assertEquals(
            $id,
            $userRole->getId(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $roleId,
            $userRole->getRoleId(),
            'Setter or getter failed.'
        );
        $this->assertEquals(
            $userId,
            $userRole->getUserId(),
            'Setter or getter failed.'
        );
    }

    /**
     * testPopulate
     *
     * @covers \RcmUser\User\Entity\UserRole::populate
     *
     * @return void
     */
    public function testPopulate()
    {
        $userRole = new UserRole();

        $id = 123;
        $roleId = 234;
        $userId = '456';

        $userRole->setId($id);
        $userRole->setRoleId($roleId);
        $userRole->setUserId($userId);

        $userRoleA = new UserRole();
        $userRoleB = [
            'id' => 789789,
            'roleId' => 123123,
            'userId' => '123123123',
        ];
        $userRoleC = 'INVALID';

        $userRoleA->populate($userRole);

        $this->assertEquals($userRole, $userRole, 'Populate failed.');

        $userRoleA->populate($userRoleB);

        $this->assertEquals(
            $userRoleB['id'],
            $userRoleA->getId(),
            'Populate failed.'
        );

        try {
            $userRoleA->populate($userRoleC);
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }

    /**
     * testJson
     *
     * @covers \RcmUser\User\Entity\UserRole::jsonSerialize
     *
     * @return void
     */
    public function testJson()
    {
        $userRole = new UserRole();

        $id = 123;
        $roleId = 234;
        $userId = '456';

        $userRole->setId($id);
        $userRole->setRoleId($roleId);
        $userRole->setUserId($userId);

        $obj = $userRole->jsonSerialize();

        $json = json_encode($obj);

        $this->assertJson($json);

        $json = json_encode($userRole);

        $this->assertJson($json);
    }
}
