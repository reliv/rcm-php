<?php

namespace RcmUser\Test\Acl\Db;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Acl\Db\DoctrineAclRoleDataMapper;

/**
 * Class DoctrineAclRoleDataMapperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Db
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    \RcmUser\Acl\Db\DoctrineAclRoleDataMapper
 */
class DoctrineAclRoleDataMapperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DoctrineAclRoleDataMapper $aclRoleDataMapper
     */
    public $aclRoleDataMapper;

    /**
     * setUp
     *
     * @return void
     */
    public function setup()
    {
        /* testing doctrine not possible
        $this->configArr = array(
            'DefaultGuestRoleIds' => array('guest'),
            'DefaultUserRoleIds' => array('user'),
            'SuperAdminRoleId' => 'admin',
            'GuestRoleId' => 'guest',
        );

        $this->config = new Config(
            $this->configArr
        );

        // entityManager//
        $this->entityManager = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        // query //
        $this->querySuccessResult = array('QUERYSUCCESS');
        $this->queryEmptyResult = array();

        //
        $this->querySuccess = $this->getMockBuilder(
            '\Doctrine\ORM\AbstractQuery'
        )
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->querySuccess->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($this->querySuccessResult));

        //
        $this->queryEmpty = $this->getMockBuilder(
            '\Doctrine\ORM\AbstractQuery'
        )
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->queryEmpty->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($this->queryEmptyResult));

        //
        $this->entityManager->expects($this->any())
            ->method('createQuery')
            ->will($this->returnValue($this->querySuccess));

        $this->entityClass = '\Some\Class';

        $this->aclRoleDataMapper = new DoctrineAclRoleDataMapper($this->config);

        $this->aclRoleDataMapper->setEntityManager($this->entityManager);
        $this->aclRoleDataMapper->setEntityClass($this->entityClass);
        */
    }

    /**
     * testFetch
     *
     * @return void
     */
    public function testFetch()
    {
        /* testing doctrine not possible
        $result = $this->aclRoleDataMapper->fetchAll();

        $this->assertEquals(
            $this->querySuccessResult,
            $result->getData()
        );

        // change result
        $this->entityManager->expects($this->any())
            ->method('createQuery')
            ->will($this->returnValue($this->queryEmpty));

        $result = $this->aclRoleDataMapper->fetchAll();

        $this->assertEquals(
            $this->queryEmptyResult,
            $result->getData()
        );
        */
    }
}
