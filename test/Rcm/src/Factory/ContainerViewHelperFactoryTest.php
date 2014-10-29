<?php
/**
 * Test for Factory ContainerViewHelperFactory
 *
 * This file contains the test for the ContainerViewHelperFactory.
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

use Rcm\Factory\ContainerViewHelperFactory;
use Rcm\View\Helper\Container;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

/**
 * Test for Factory ContainerViewHelperFactory
 *
 * Test for Factory ContainerViewHelperFactory
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
class ContainerViewHelperFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\ContainerViewHelperFactory
     */
    public function testCreateService()
    {
        $mockPluginManager = $this
            ->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager = $this
            ->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainerRepo = $this
            ->getMockBuilder('\Rcm\Repository\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockContainerRepo));

        $sm = new ServiceManager();
        $sm->setService('Doctrine\ORM\EntityManager', $mockEntityManager);
        $sm->setService('Rcm\Service\PluginManager', $mockPluginManager);

        $helperManager = new HelperPluginManager();
        $helperManager->setServiceLocator($sm);

        $factory = new ContainerViewHelperFactory();
        $object = $factory->createService($helperManager);

        $this->assertTrue($object instanceof Container);
    }
}