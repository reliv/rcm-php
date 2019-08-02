<?php

namespace RcmUser\Test\Acl\Service;

use RcmUser\Acl\Service\AuthorizeServiceFactory;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class AuthorizeServiceTest
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
 * @covers    AuthorizeServiceFactory
 */
class AuthorizeServiceFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {

        $factory = new AuthorizeServiceFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AuthorizeService::class,
            $service
        );

        //
        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AclResourceService::class,
            $service->getAclResourceService()
        );

        $this->assertInstanceOf(
            \RcmUser\Acl\Service\AclDataService::class,
            $service->getAclDataService()
        );
    }
}
