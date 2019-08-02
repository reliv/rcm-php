<?php

namespace RcmUser\Test\Acl;

use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Entity\AclRole;
use RcmUser\Acl\RcmUserAcl;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class RcmUserAclTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\RcmUserAcl
 */
class RcmUserAclTest extends Zf2TestCase
{

    public function testGetAccess()
    {

        $acl = new RcmUserAcl();

        $acl->addRole(new AclRole('guest'))
            ->addRole(new AclRole('member'))
            ->addRole(new AclRole('admin'));

        $parents = ['guest', 'member', 'admin'];
        $acl->addRole(new AclRole('someUser'), $parents);

        $acl->addResource(new AclResource('someresource'));

        $acl->deny('guest', 'someresource');
        $acl->allow('member', 'someresource');

        //echo "\n*** RESULT: " .
        //var_export($acl->getAccess('someUser', 'someresource'), true);
    }
}
