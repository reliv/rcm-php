<?php

namespace RcmUser\Test\User\Service;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Service\UserRoleServiceFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserRoleServiceTest
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
 * @covers    UserRoleServiceFactory
 */
class UserRoleServiceFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserRoleServiceFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Service\UserRoleService::class,
            $service
        );
        //
    }
}
