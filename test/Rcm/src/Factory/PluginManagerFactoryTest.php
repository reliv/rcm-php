<?php
/**
 * Test for Factory PluginManagerFactory
 *
 * This file contains the test for the PluginManagerFactory.
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

use Rcm\Factory\PluginManagerFactory;
use Rcm\Service\PluginManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory PluginManagerFactory
 *
 * Test for Factory PluginManagerFactory
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
class PluginManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\PluginManagerFactory
     */
    public function testCreateService()
    {
        $mockEntityManager = $this->getMockBuilder(
            '\Doctrine\ORM\EntityManager'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockModuleManager = $this
            ->getMockBuilder('\Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockViewRenderer = $this
            ->getMockBuilder('\Zend\View\Renderer\PhpRenderer')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRequest = $this
            ->getMockBuilder('Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $mockCache = $this->getMockBuilder('Zend\Cache\Storage\Adapter\Memory')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEventManager = $this->getMockBuilder('Zend\EventManager\EventManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockView = $this->getMockBuilder('Zend\View\View')
            ->disableOriginalConstructor()
            ->getMock();

        $mockView->expects($this->any())
            ->method('getEventManager')
            ->will($this->returnValue($mockEventManager));

        $mockViewManager = $this->getMockBuilder('Zend\Mvc\View\Http\ViewManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockViewManager->expects($this->any())
            ->method('getView')
            ->will($this->returnValue($mockView));

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Doctrine\ORM\EntityManager',
            $mockEntityManager
        );
        $serviceManager->setService('moduleManager', $mockModuleManager);
        $serviceManager->setService('ViewRenderer', $mockViewRenderer);
        $serviceManager->setService('request', $mockRequest);
        $serviceManager->setService('Rcm\Service\Cache', $mockCache);
        $serviceManager->setService('config', array());
        $serviceManager->setService('ViewManager', $mockViewManager);

        $factory = new PluginManagerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof PluginManager);
    }
}