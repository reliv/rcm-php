<?php

namespace RcmUser\Test\User\Service;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Data\UserValidatorFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class UserValidatorTest
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
 * @covers    UserValidatorFactory
 */
class UserValidatorFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new UserValidatorFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Data\UserValidatorInterface::class,
            $service
        );
        //
    }
}
