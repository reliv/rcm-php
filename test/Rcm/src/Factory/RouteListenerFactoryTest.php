<?php
/**
 * Test for Factory RouteListenerFactory
 *
 * This file contains the test for the RouteListenerFactory.
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

use Rcm\EventListener\RouteListener;
use Rcm\Factory\RouteListenerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory RouteListenerFactory
 *
 * Test for Factory RouteListenerFactory
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
class RouteListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\RouteListenerFactory
     */
    public function testCreateService()
    {
        $mockDomainManager = $this->getMockBuilder('\Rcm\Service\DomainManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirectManager = $this->getMockBuilder('\Rcm\Service\RedirectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Rcm\Service\DomainManager', $mockDomainManager);
        $serviceLocator->setService(
            'Rcm\Service\RedirectManager',
            $mockRedirectManager
        );

        $factory = new RouteListenerFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof RouteListener);
    }
}