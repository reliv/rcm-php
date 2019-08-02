<?php

namespace RcmUser\Test\User\Data;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Data\UserDataPreparerFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserDataPreparerTest
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
 * @covers    UserDataPreparerFactory
 */
class UserDataPreparerFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserDataPreparerFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Data\UserDataPreparerInterface::class,
            $service
        );
    }
}
