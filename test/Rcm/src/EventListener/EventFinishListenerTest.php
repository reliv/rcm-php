<?php
/**
 * Unit Test for the Event Finish Listener Event
 *
 * This file contains the unit test for Event Finish Listener Event
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

namespace RcmTest\EventListener;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\EventListener\EventFinishListener;
use Rcm\Http\Response;
use Zend\Http\Response as ZendHttpResponse;
use Zend\Mvc\MvcEvent;

/**
 * Unit Test for Event Finish Listener Event
 *
 * Unit Test for Event Finish Listener Event
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class EventFinishListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener::__construct
     */
    public function testConstructor()
    {
        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new EventFinishListener(
            $mockResponseHandler
        );

        $this->assertTrue($listener instanceof EventFinishListener);
    }

    /**
     * Test Constructor Only Accepts a EventFinishListener object
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener::__construct
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testConstructorOnlyAcceptsAEventFinishListenerObject()
    {
        $mockResponseHandler = $this->getMockBuilder('Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        new EventFinishListener(
            $mockResponseHandler
        );
    }

    /**
     * Test Process Rcm Responses
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener::processRcmResponses
     */
    public function testProcessRcmResponses()
    {

        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponseHandler->expects($this->once())
            ->method('processResponse');

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new EventFinishListener(
            $mockResponseHandler
        );

        $event = new MvcEvent();

        $response = new Response();
        $response->setStatusCode(404);

        $event->setResult($response);

        $listener->processRcmResponses($event);
    }

    /**
     * Test Process Rcm Responses with a Zend Http Response.  Method should
     * return early.
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener::processRcmResponses
     */
    public function testProcessRcmResponsesWithZendResponseObject()
    {

        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponseHandler->expects($this->never())
            ->method('processResponse');

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new EventFinishListener(
            $mockResponseHandler
        );

        $event = new MvcEvent();

        $response = new ZendHttpResponse();
        $response->setStatusCode(404);

        $event->setResult($response);

        $listener->processRcmResponses($event);
    }
}
