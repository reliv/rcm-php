<?php
/**
 * DoctrineAclRuleTest.php
 *
 * TEST
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

use RcmUser\Acl\Entity\DoctrineAclRule;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class DoctrineAclRuleTest
 *
 * TEST
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/
 * @covers    \RcmUser\Acl\Entity\DoctrineAclRule
 */
class DoctrineAclRuleTest extends Zf2TestCase
{
    /**
     * testSetGet
     *
     * @covers \RcmUser\Acl\Entity\DoctrineAclRule
     *
     * @return void
     */
    public function testSetGet()
    {
        $aclRule = new DoctrineAclRule();

        $roleId = '123';
        $id = 321;

        $aclRule->setId($id);
        $aclRule->setRoleId($roleId);

        $this->assertTrue(
            $aclRule->getId() === $id,
            'Setter or getter failed.'
        );
        $this->assertTrue(
            $aclRule->getRoleId() === $roleId,
            'Setter or getter failed.'
        );
    }
}
