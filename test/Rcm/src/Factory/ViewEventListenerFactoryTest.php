<?php
/**
 * Test for Factory ViewEventListenerFactory
 *
 * This file contains the test for the ViewEventListenerFactory.
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

use Rcm\EventListener\ViewEventListener;
use Rcm\Factory\ViewEventListenerFactory;
use Zend\ServiceManager\ServiceManager;

/**
 * Test for Factory ViewEventListenerFactory
 *
 * Test for Factory ViewEventListenerFactory
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
class ViewEventListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Generic test for the constructor
     *
     * @return null
     * @covers \Rcm\Factory\ViewEventListenerFactory
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

        $factory = new ViewEventListenerFactory();
        $object = $factory->createService($serviceManager);

        $this->assertTrue($object instanceof ViewEventListener);
    }
}