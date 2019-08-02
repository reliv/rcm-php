<?php

namespace RcmUser\Test\User\Password;

use RcmUser\Test\Zf2TestCase;
use RcmUser\User\Password\EncryptorFactory;

require_once __DIR__ . '/../../../Zf2TestCase.php';

/**
 * Class EncryptorTest
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
 * @covers    EncryptorFactory
 */
class EncryptorFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new EncryptorFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \Zend\Crypt\Password\PasswordInterface::class,
            $service
        );
    }
}
