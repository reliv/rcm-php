<?php
///**
// * Test for Factory DispatchListenerFactory
// *
// * This file contains the test for the DispatchListenerFactory.
// *
// * PHP version 5.3
// *
// * LICENSE: BSD
// *
// * @category  Reliv
// * @package   RcmAdmin
// * @author    Westin Shafer <wshafer@relivinc.com>
// * @copyright 2017 Reliv International
// * @license   License.txt New BSD License
// * @version   GIT: <git_id>
// * @link      http://github.com/reliv
// */
//
//namespace RcmAdminTest\Factory;
//
//require_once __DIR__ . '/../autoload.php';
//
//use RcmAdmin\EventListener\DispatchListener;
//use RcmAdmin\Factory\DispatchListenerFactory;
//use Zend\ServiceManager\ServiceManager;
//
///**
// * Test for Factory DispatchListenerFactory
// *
// * Test for Factory DispatchListenerFactory
// *
// * @category  Reliv
// * @package   RcmAdmin
// * @author    Westin Shafer <wshafer@relivinc.com>
// * @copyright 2012 Reliv International
// * @license   License.txt New BSD License
// * @version   Release: 1.0
// * @link      http://github.com/reliv
// *
// */
//class DispatchListenerFactoryTest extends \PHPUnit_Framework_TestCase
//{
//    /**
//     * Generic test for the constructor
//     *
//     * @return void
//     * @covers \RcmAdmin\Factory\DispatchListenerFactory
//     */
//    public function testCreateService()
//    {
//        $mockController = $this
//            ->getMockBuilder(\RcmAdmin\Controller\AdminPanelController::class)
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $serviceManager = new ServiceManager();
//
//        $serviceManager->setService(
//            \RcmAdmin\Controller\AdminPanelController::class,
//            $mockController
//        );
//
//        $factory = new DispatchListenerFactory();
//        $object = $factory->__invoke($serviceManager);
//
//        $this->assertTrue($object instanceof DispatchListener);
//    }
//}
