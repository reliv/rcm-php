<?php
/**
 * Unit Test for the Dispatch Listener Event
 *
 * This file contains the unit test for Dispatch Listener Event
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmAdminTest\EventListener;

use RcmAdmin\EventListener\DispatchListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for Dispatch Listener Event
 *
 * Unit Test for Dispatch Listener Event
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DispatchListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockController;

    /** @var \RcmAdmin\EventListener\DispatchListener */
    protected $listener;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->mockController = $this
            ->getMockBuilder('\RcmAdmin\Controller\AdminPanelController')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \RcmAdmin\Controller\AdminPanelController $mockController */
        $mockController = $this->mockController;

        $serviceManager = new ServiceManager();

        $serviceManager->setService('RcmAdmin\Controller\AdminPanelController', $mockController);

        $this->listener = new DispatchListener($serviceManager);
    }

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \RcmAdmin\EventListener\DispatchListener::__construct
     */
    public function testConstructor()
    {
        $this->assertTrue($this->listener instanceof DispatchListener);
    }

    /**
     * Test Get Admin Panel Returns Panel
     *
     * @return void
     *
     * @covers \RcmAdmin\EventListener\DispatchListener::getAdminPanel
     */
    public function testGetAdminPanel()
    {
        $layoutViewMock = new ViewModel();

        $event = new MvcEvent();
        $routeMatch = new RouteMatch([]);
        $event->setRouteMatch($routeMatch);
        $event->setViewModel($layoutViewMock);

        $mockAdminPanel = new ViewModel();
        $mockAdminPanel->setVariable('This-is-a-test', true);

        $this->mockController->expects($this->once())
            ->method('getAdminWrapperAction')
            ->will($this->returnValue($mockAdminPanel));

        $this->listener->getAdminPanel($event);

        /** @var \Zend\View\Model\ViewModel $panelView */
        $children = $layoutViewMock->getChildrenByCaptureTo('rcmAdminPanel');

        $this->assertNotEmpty($children);

        $this->assertCount(1, $children);

        $panelView = $children[0];

        $this->assertTrue($panelView instanceof ViewModel);

        /* Verify correct model */
        $testViewVar = $panelView->getVariable('This-is-a-test');

        $this->assertTrue($testViewVar);
    }

    /**
     * Test Get Admin Panel No Panel Returned
     *
     * @return void
     *
     * @covers \RcmAdmin\EventListener\DispatchListener::getAdminPanel
     */
    public function testGetAdminPanelReturnsNoView()
    {
        $layoutViewMock = new ViewModel();

        $event = new MvcEvent();
        $routeMatch = new RouteMatch([]);
        $event->setRouteMatch($routeMatch);
        $event->setViewModel($layoutViewMock);

        $this->mockController->expects($this->once())
            ->method('getAdminWrapperAction')
            ->will($this->returnValue(null));

        $this->listener->getAdminPanel($event);

        /** @var \Zend\View\Model\ViewModel $panelView */
        $children = $layoutViewMock->getChildrenByCaptureTo('rcmAdminPanel');

        $this->assertEmpty($children);
    }
}