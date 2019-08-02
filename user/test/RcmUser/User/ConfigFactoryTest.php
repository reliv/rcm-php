<?php

namespace RcmUser\Test\User;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\ConfigFactory;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class ConfigTest
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
 * @covers    ConfigFactory
 */
class ConfigFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new ConfigFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\User\Config::class,
            $service
        );
    }
}
