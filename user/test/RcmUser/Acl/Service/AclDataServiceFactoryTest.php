<?php

namespace RcmUser\Test\Acl\Service;

require_once __DIR__ . '/../../../Zf2TestCase.php';

use RcmUser\Acl\Service\AclDataServiceFactory;
use RcmUser\Test\Zf2TestCase;

/**
 * Class AclDataServiceTest
 *
 * AclDataServiceTest
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Acl\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    AclDataServiceFactory
 */
class AclDataServiceFactoryTest extends Zf2TestCase
{

    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new AclDataServiceFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AclDataService::class,
            $service
        );

        //
        $this->assertInstanceOf(
            \RcmUser\Acl\Db\AclRoleDataMapperInterface::class,
            $service->getAclRoleDataMapper()
        );

        $this->assertInstanceOf(
            \RcmUser\Acl\Db\AclRuleDataMapperInterface::class,
            $service->getAclRuleDataMapper()
        );
    }
}
