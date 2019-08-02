<?php

namespace RcmUser\Test\User\Event;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Event\UserDataServiceListenersFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserDataServiceListenersTest
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
 * @covers    UserDataServiceListenersFactory
 */
class UserDataServiceListenersFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserDataServiceListenersFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Event\UserDataServiceListeners::class,
            $service
        );

        $this->assertInstanceOf(
            \RcmUser\User\Db\UserDataMapperInterface::class,
            $service->getUserDataMapper()
        );
    }
}
