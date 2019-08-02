<?php

namespace RcmUser\Test\Acl\Db;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Acl\Db\AclRuleDataMapper;
use RcmUser\Acl\Entity\AclRule;

/**
 * Class AclRuleDataMapperTest
 *
 * AclRuleDataMapperTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Db\AclRuleDataMapper
 */
class AclRuleDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->aclRuleDataMapper = new AclRuleDataMapper();
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
        $result = $this->aclRuleDataMapper->fetchAll();
    }

    /**
     * testFetchByRole
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByRole()
    {
        $result = $this->aclRuleDataMapper->fetchByRole('roleId');
    }

    /**
     * testFetchByRule
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByRule()
    {
        $result = $this->aclRuleDataMapper->fetchByRule('ruletype');
    }


    /**
     * testFetchByResources
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByResources()
    {
        $result = $this->aclRuleDataMapper->fetchByResources([]);
    }
    /**
     * testFetchByResource
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByResource()
    {
        $result = $this->aclRuleDataMapper->fetchByResource('resourceid');
    }

    /**
     * testFetchByResourcePrivilege
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByResourcePrivilege()
    {
        $result = $this->aclRuleDataMapper->fetchByResourcePrivilege('resourceid', 'privilege');
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
        $aclRule = new AclRule();
        $result = $this->aclRuleDataMapper->create($aclRule);
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
        $aclRule = new AclRule();
        $result = $this->aclRuleDataMapper->update($aclRule);
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
        $aclRule = new AclRule();
        $result = $this->aclRuleDataMapper->read($aclRule);
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
        $aclRule = new AclRule();
        $result = $this->aclRuleDataMapper->delete($aclRule);
    }
}
