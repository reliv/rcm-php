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

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use Rcm\Service\ContainerManager;
use Rcm\Factory\ContainerManagerFactory;
use RcmTest\Base\BaseTestCase;
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
class ContainerManagerFactoryTest extends BaseTestCase
{
    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->addModule('Rcm');
        parent::setUp();
    }

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

        $mockEntityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache = $this->getMockBuilder('\Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $sm = new ServiceManager();
        $sm->setService('Rcm\Service\SiteManager', $mockSiteManager);
        $sm->setService('Rcm\Service\PluginManager', $mockPluginManager);
        $sm->setService('Doctrine\ORM\EntityManager', $mockEntityManager);
        $sm->setService('Rcm\Service\Cache', $mockCache);

        $factory = new ContainerManagerFactory();
        $object = $factory->createService($sm);

        $this->assertTrue($object instanceof ContainerManager);
    }
}