<?php

namespace RcmUser\Test\Provider;

use RcmUser\Provider\RcmUserAclResourceProviderFactory;
use RcmUser\Test\Zf2TestCase;

require_once __DIR__ . '/../../Zf2TestCase.php';

/**
 * Class RcmUserAclResourceProviderTest
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Test\Service\Factory
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 * @covers    RcmUserAclResourceProviderFactory
 */
class RcmUserAclResourceProviderFactoryTest extends Zf2TestCase
{
    /**
     * test
     *
     * @return void
     */
    public function test()
    {
        $factory = new RcmUserAclResourceProviderFactory();

        $service = $factory->__invoke($this->getMockServiceLocator());
        $this->assertInstanceOf(
            \RcmUser\Provider\RcmUserAclResourceProvider::class,
            $service
        );
        //
    }
}
