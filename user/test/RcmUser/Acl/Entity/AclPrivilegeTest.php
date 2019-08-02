<?php
/**
 * AclPrivilegeTest.php
 *
 * LongDescHere
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

use RcmUser\Acl\Entity\AclPrivilege;
use RcmUser\Exception\RcmUserException;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AclPrivilegeTest
 *
 * AclPrivilegeTest
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
 * @covers    \RcmUser\Acl\Entity\AclPrivilege
 */
class AclPrivilegeTest extends Zf2TestCase
{

    /**
     * testSetGet
     *
     * @return void
     */
    public function testSetGet()
    {
        $privileges = ['priv1', 'priv2'];

        $privilege = new AclPrivilege($privileges);

        $this->assertEquals(
            count($privileges),
            count($privilege->getPrivileges()),
            'Constructor Set or get failed.'
        );

        $privileges[] = 'priv3';

        $privilege->setPrivileges($privileges);

        $this->assertEquals(
            count($privileges),
            count($privilege->getPrivileges()),
            'Set or get failed.'
        );

        $privilege->setPrivilege('priv4');

        $testPriv = $privilege->getPrivilege('priv4');

        $this->assertEquals(
            'priv4',
            $testPriv,
            'Set or get failed.'
        );

        $testPriv = $privilege->getPrivilege('nope');

        $this->assertNull($testPriv, 'Set or get failed.');

        $testStr = 'p1,p2,p3';

        $prepPrivs = $privilege->preparePrivileges($testStr);

        $this->assertEquals(
            3,
            count($prepPrivs),
            'Prepare string failed.'
        );

        $json = json_encode($privilege);

        $this->assertJson($json, 'JSON encode failed');

        $iter = $privilege->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iter, 'Get iterator failed');

        $strPriv = (string)$privilege;

        $this->assertTrue(is_string($strPriv), "toString failed");

        try {
            $privilege->setPrivilege('N*P#_^^^^');
        } catch (RcmUserException $e) {
            $this->assertInstanceOf(\RcmUser\Exception\RcmUserException::class, $e);
            return;
        }

        $this->fail("Expected exception not thrown");
    }
}
