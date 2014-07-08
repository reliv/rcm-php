<?php
/**
 * Test for Factory EventFinishListenerFactory
 *
 * This file contains the test for the EventFinishListenerFactory.
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

use Rcm\EventListener\EventFinishListener;
use Rcm\Factory\EventFinishListenerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory EventFinishListenerFactory
 *
 * Test for Factory EventFinishListenerFactory
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
class EventFinishListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\EventFinishListenerFactory
     */
    public function testCreateService()
    {
        $mockResponseHandler = $this->getMockBuilder(
            '\Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Rcm\Service\ResponseHandler',
            $mockResponseHandler
        );

        $factory = new EventFinishListenerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof EventFinishListener);
    }
}