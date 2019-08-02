<?php

namespace RcmUser\Test\Acl\Entity;

use RcmUser\Acl\Entity\NamespaceAclRole;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class NamespaceAclRoleTest
 *
 * NamespaceAclRoleTest
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
 */
class NamespaceAclRoleTest extends Zf2TestCase
{

    /**
     * testSetGet
     *
     * @return void
     */
    public function testSetGet()
    {

        $nameSpaceAclRole = new NamespaceAclRole(
            'admin',
            'guest'
        );

        $nameSpaceAclRole->setRoleId('myrole');

        $ns = $nameSpaceAclRole->getNamespace();

        $this->assertEquals('myrole', $ns);

        $nameSpaceAclRole->setNamespace('TESTNS');

        $ns = $nameSpaceAclRole->getNamespace();

        $this->assertEquals('TESTNS', $ns);

        $obj = $nameSpaceAclRole->jsonSerialize();

        $this->assertEquals('TESTNS', $obj->namespace);
    }
}
