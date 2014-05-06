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

use Rcm\Service\PageManager;
use Rcm\Factory\PageManagerFactory;
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

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
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

        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\SiteManager', $mockSiteManager);
        $sm->setService('Rcm\Service\PluginManager', $mockPluginManager);
        $sm->setService('Doctrine\ORM\EntityManager', $mockEntityManager);
        $sm->setService('Rcm\Service\Cache', $mockCache);

        $factory = new PageManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof PageManager);
    }
}