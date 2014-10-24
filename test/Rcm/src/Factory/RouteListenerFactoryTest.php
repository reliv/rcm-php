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
        $mockEm = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirectRepo = $this->getMockBuilder('\Rcm\Repository\Redirect')
            ->disableOriginalConstructor()
            ->getMock();

        $mockEm->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($mockRedirectRepo));

        $mockCurrentSite = $this->getMockBuilder('\Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService(
            'Doctrine\ORM\EntityManager',
            $mockEm
        );
        $serviceLocator->setService(
            'Rcm\Service\CurrentSite',
            $mockCurrentSite
        );
        $serviceLocator->setService('config', array());

        $factory = new RouteListenerFactory();
        $object = $factory->createService($serviceLocator);

        $this->assertTrue($object instanceof RouteListener);
    }
}