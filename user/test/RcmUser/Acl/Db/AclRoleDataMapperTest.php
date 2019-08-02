<?php
/**
 * AclRoleDataMapperTest.php
 *
 * AclRoleDataMapperTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Test\Acl\Db;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Acl\Db\AclRoleDataMapper;
use RcmUser\Acl\Entity\AclRole;
use RcmUser\Config\Config;

/**
 * Class AclRoleDataMapperTest
 *
 * AclRoleDataMapperTest
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
 * @covers    \RcmUser\Acl\Db\AclRoleDataMapper
 */
class AclRoleDataMapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * setUp
     *
     * @return void
     */
    public function setUp()
    {
        $this->configArr = [
            'DefaultGuestRoleIds' => ['guest'],
            'DefaultUserRoleIds' => ['user'],
            'SuperAdminRoleId' => 'admin',
            'GuestRoleId' => 'guest',
        ];

        $this->config = new Config(
            $this->configArr
        );

        $this->aclRoleDataMapper = new AclRoleDataMapper($this->config);
    }


    /**
     * testGeneralFetch
     *
     * @return void
     */
    public function testGeneralFetch()
    {
        $result = $this->aclRoleDataMapper->fetchSuperAdminRoleId();

        $this->assertEquals(
            $this->configArr['SuperAdminRoleId'],
            $result->getData()
        );

        $result = $this->aclRoleDataMapper->fetchGuestRoleId();

        $this->assertEquals(
            $this->configArr['GuestRoleId'],
            $result->getData()
        );

        $result = $this->aclRoleDataMapper->fetchDefaultGuestRoleIds();

        $this->assertEquals(
            $this->configArr['DefaultGuestRoleIds'],
            $result->getData()
        );

        $result = $this->aclRoleDataMapper->fetchDefaultUserRoleIds();

        $this->assertEquals(
            $this->configArr['DefaultUserRoleIds'],
            $result->getData()
        );
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
        $result = $this->aclRoleDataMapper->fetchAll();
    }

    /**
     * testFetchByRoleId
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByRoleId()
    {
        $result = $this->aclRoleDataMapper->fetchByRoleId('roleId');
    }

    /**
     * testFetchByParentRoleId
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchByParentRoleId()
    {
        $result = $this->aclRoleDataMapper->fetchByParentRoleId('roleId');
    }

    /**
     * testFetchRoleLineage
     *
     * @expectedException \RcmUser\Exception\RcmUserException
     *
     * @return void
     */
    public function testFetchRoleLineage()
    {
        $result = $this->aclRoleDataMapper->fetchRoleLineage('roleId');
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
        $acRole = new AclRole();
        $result = $this->aclRoleDataMapper->create($acRole);
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
        $acRole = new AclRole();
        $result = $this->aclRoleDataMapper->read($acRole);
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
        $acRole = new AclRole();
        $result = $this->aclRoleDataMapper->update($acRole);
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
        $acRole = new AclRole();
        $result = $this->aclRoleDataMapper->delete($acRole);
    }
}
