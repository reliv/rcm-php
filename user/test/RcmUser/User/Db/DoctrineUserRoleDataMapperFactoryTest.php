<?php

namespace RcmUser\Test\User\Db;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Db\DoctrineUserRoleDataMapperFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class DoctrineUserRoleDataMapperTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\User\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    DoctrineUserRoleDataMapperFactory
 */
class DoctrineUserRoleDataMapperFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new DoctrineUserRoleDataMapperFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Db\DoctrineUserRoleDataMapper::class,
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

        /*
        $this->assertInstanceOf(
            \RcmUser\Acl\Db\AclRoleDataMapperInterface::class,
            $service->getUserDataPreparer()
        );
        */
    }
}
