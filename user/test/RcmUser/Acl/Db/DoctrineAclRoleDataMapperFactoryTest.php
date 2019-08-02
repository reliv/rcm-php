<?php

namespace RcmUser\Test\Acl\Db;

use RcmUser\Acl\Db\DoctrineAclRoleDataMapperFactory;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class DoctrineAclRoleDataMapperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    DoctrineAclRoleDataMapperFactory
 */
class DoctrineAclRoleDataMapperFactoryTest extends Zf2TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new DoctrineAclRoleDataMapperFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\Acl\Db\DoctrineAclRoleDataMapper::class,
            $service
        );

        //
        $this->assertInstanceOf(
            \Doctrine\ORM\EntityManager::class,
            $service->getEntityManager()
        );

        $this->assertTrue(
            is_string($service->getEntityClass())
        );
    }
}
