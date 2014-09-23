<?php
/**
 * Test for Factory PageManagerFactory
 *
 * This file contains the test for the PageManagerFactory.
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

use Rcm\Factory\PageManagerFactory;
use Rcm\Service\PageManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory PageManagerFactory
 *
 * Test for Factory PageManagerFactory
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
class PageManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\PageManagerFactory
     */
    public function testCreateService()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPluginManager = $this->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageManager = $this->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->once())
            ->method('getPageManager')
            ->will($this->returnValue($mockPageManager));

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

        $mockLayoutValidator = $this
            ->getMockBuilder('Rcm\Validator\MainLayout')
            ->disableOriginalConstructor()
            ->getMock();


        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );
        $serviceLocator->setService(
            'Rcm\Service\PluginManager',
            $mockPluginManager
        );
        $serviceLocator->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );
        $serviceLocator->setService('Rcm\Service\Cache', $mockCache);
        $serviceLocator->setService(
            'Rcm\Validator\MainLayout',
            $mockLayoutValidator
        );

        $factory = new PageManagerFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof PageManager);
    }
}