<?php
/**
 * Test for Factory ContainerManagerFactory
 *
 * This file contains the test for the ContainerManagerFactory.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmTest\Factory;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Factory\ContainerManagerFactory;
use Rcm\Service\ContainerManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory ContainerManagerFactory
 *
 * Test for Factory ContainerManagerFactory
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class ContainerManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\ContainerManagerFactory
     */
    public function testCreateService()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainerManager = $this->getMockBuilder('\Rcm\Service\ContainerManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getContainerManager')
            ->will($this->returnValue($mockContainerManager));

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );

        $factory = new ContainerManagerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof ContainerManager);
    }
}
