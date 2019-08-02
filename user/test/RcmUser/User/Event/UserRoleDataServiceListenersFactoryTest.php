<?php

namespace RcmUser\Test\User\Event;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Event\UserRoleDataServiceListenersFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserRoleDataServiceListenersTest
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
 * @covers    UserRoleDataServiceListenersFactory
 */
class UserRoleDataServiceListenersFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserRoleDataServiceListenersFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Event\UserRoleDataServiceListeners::class,
            $service
        );
        //

        $this->assertInstanceOf(
            \RcmUser\User\Service\UserRoleService::class,
            $service->getUserRoleService()
        );
    }
}
