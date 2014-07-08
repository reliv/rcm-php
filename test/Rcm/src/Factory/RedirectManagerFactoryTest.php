<?php
/**
 * Test for Factory RedirectManagerFactory
 *
 * This file contains the test for the RedirectManagerFactory.
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

use Rcm\Factory\RedirectManagerFactory;
use Rcm\Service\RedirectManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory RedirectManagerFactory
 *
 * Test for Factory RedirectManagerFactory
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
class RedirectManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\RedirectManagerFactory
     */
    public function testCreateService()
    {
        $mockRedirectRepo = $this->getMockBuilder('\Rcm\Repository\Redirect')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockRedirectRepo));

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );

        $serviceLocator->setService(
            'Rcm\Service\SiteManager',
            $mockSiteManager
        );

        $serviceLocator->setService('Rcm\Service\Cache', $mockCache);

        $factory = new RedirectManagerFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof RedirectManager);
    }
}