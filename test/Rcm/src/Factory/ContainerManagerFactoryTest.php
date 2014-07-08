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

        $mockPluginManager = $this->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockRepo = $this->getMockBuilder('\Rcm\Repository\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockRepo));

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );
        $serviceManager->setService(
            'Rcm\Service\PluginManager',
            $mockPluginManager
        );
        $serviceManager->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );
        $serviceManager->setService('Rcm\Service\Cache', $mockCache);

        $factory = new ContainerManagerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof ContainerManager);
    }
}
