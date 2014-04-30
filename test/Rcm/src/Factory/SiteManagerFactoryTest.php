<?php
/**
 * Test for Factory SiteManagerFactory
 *
 * This file contains the test for the SiteManagerFactory.
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

use Rcm\Service\SiteManager;
use Rcm\Factory\SiteManagerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory SiteManagerFactory
 *
 * Test for Factory SiteManagerFactory
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
class SiteManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\SiteManagerFactory
     */
    public function testCreateService()
    {
        $mockDomainManager = $this->getMockBuilder('\Rcm\Service\DomainManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest = $this->getMockBuilder('\Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService('Rcm\Service\DomainManager', $mockDomainManager);
        $serviceManager->setService('Doctrine\ORM\EntityManager', $mockEntityManager);
        $serviceManager->setService('Rcm\Service\Cache', $mockCache);
        $serviceManager->setService('request', $mockRequest);

        $factory = new SiteManagerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof SiteManager);
    }
}