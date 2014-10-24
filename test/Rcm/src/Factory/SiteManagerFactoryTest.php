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

use Rcm\Entity\Site;
use Rcm\Factory\SiteManagerFactory;
use Rcm\Service\SiteManager;
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

        $mockPluginManager = $this->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteRepo = $this->getMockBuilder('\Rcm\Repository\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockSiteRepo));

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest = $this->getMockBuilder('\Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest->expects($this->any())
            ->method('getServer')
            ->will($this->returnValue(new \Zend\Stdlib\Parameters()));

        $mockLayoutValidator = $this->getMockBuilder('\Rcm\Validator\MainLayout')
            ->disableOriginalConstructor()
            ->getMock();

        $currentSite = new Site();
        $currentSite->setSiteId(1);

        $serviceManager = new ServiceManager();

        $serviceManager->setService(
            'Rcm\Service\CurrentSite',
            $currentSite
        );

        $serviceManager->setService(
            'Rcm\Service\PluginManager',
            $mockPluginManager
        );

        $serviceManager->setService(
            'Rcm\Service\Cache',
            $mockCache
        );

        $serviceManager->setService('Rcm\Validator\MainLayout', $mockLayoutValidator);

        $serviceManager->setService('Rcm\Repository\Site', $mockSiteRepo);
        $serviceManager->setService('Doctrine\ORM\EntityManager',$mockEntityManager);
        $serviceManager->setService('config', array());
        $serviceManager->setService('request', $mockRequest);


        $factory = new SiteManagerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof SiteManager);
    }
}