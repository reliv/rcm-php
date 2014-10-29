<?php
/**
 * Unit Test for the View Event Listener
 *
 * This file contains the unit test for View Event Listener
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

use Rcm\EventListener\ViewEventListener;
use Rcm\Http\Response;
use Zend\Http\Response as ZendHttpResponse;
use Zend\View\ViewEvent;

/**
 * Unit Test for View Event Listener
 *
 * Unit Test for View Event Listener
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ViewEventListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test Constructor
     *
     * @return void
     *
     * @covers \Rcm\EventListener\ViewEventListener::__construct
     */
    public function testConstructor()
    {
        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new ViewEventListener(
            $mockResponseHandler
        );

        $this->assertTrue($listener instanceof ViewEventListener);
    }

    /**
     * Test Constructor Only Accepts a EventFinishListener object
     *
     * @return void
     *
     * @covers \Rcm\EventListener\ViewEventListener::__construct
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testConstructorOnlyAcceptsAEventFinishListenerObject()
    {
        $mockResponseHandler = $this->getMockBuilder('Rcm\Entity\Site')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        new ViewEventListener(
            $mockResponseHandler
        );
    }

    /**
     * Test Process Rcm Responses
     *
     * @return void
     *
     * @covers \Rcm\EventListener\ViewEventListener::processRcmResponses
     */
    public function testProcessRcmResponses()
    {
        $event = new ViewEvent();

        $response = new Response();
        $response->setStatusCode(404);

        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponseHandler->expects($this->once())
            ->method('processResponse');

        $event->setResponse($response);

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new ViewEventListener(
            $mockResponseHandler
        );

        $listener->processRcmResponses($event);
    }

    /**
     * Test Process Rcm Responses
     *
     * @return void
     *
     * @covers \Rcm\EventListener\ViewEventListener::processRcmResponses
     */
    public function testProcessRcmResponsesIgnoresJsonRenderer()
    {
        $event = new ViewEvent();

        $response = new Response();
        $response->setStatusCode(404);

        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponseHandler->expects($this->never())
            ->method('processResponse');

        $mockContainerPlugin = $this->getMockBuilder(
            '\Rcm\View\Helper\Container'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainerPlugin->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $mockRenderer = $this->getMockBuilder(
            '\Zend\View\Renderer\JsonRenderer'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockRenderer->expects($this->any())
            ->method('plugin')
            ->will($this->returnValue($mockContainerPlugin));

        /** @var \Zend\View\Renderer\PhpRenderer $mockRenderer */
        $event->setRenderer($mockRenderer);

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new ViewEventListener(
            $mockResponseHandler
        );

        $listener->processRcmResponses($event);
    }

    /**
     * Test Process Rcm Responses with a Zend Http Response.  Method should
     * return early.
     *
     * @return void
     *
     * @covers \Rcm\EventListener\ViewEventListener::processRcmResponses
     */
    public function testProcessRcmResponsesWithZendResponseObject()
    {
        $event = new ViewEvent();

        $response = new ZendHttpResponse();
        $response->setStatusCode(404);

        $mockResponseHandler = $this->getMockBuilder(
            'Rcm\Service\ResponseHandler'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockResponseHandler->expects($this->never())
            ->method('processResponse');

        $mockContainerPlugin = $this->getMockBuilder(
            '\Rcm\View\Helper\Container'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockContainerPlugin->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($response));

        $mockRenderer = $this->getMockBuilder('\Zend\View\Renderer\PhpRenderer')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRenderer->expects($this->any())
            ->method('plugin')
            ->will($this->returnValue($mockContainerPlugin));

        /** @var \Zend\View\Renderer\PhpRenderer $mockRenderer */
        $event->setRenderer($mockRenderer);

        /** @var \Rcm\Service\ResponseHandler $mockResponseHandler */
        $listener = new ViewEventListener(
            $mockResponseHandler
        );

        $listener->processRcmResponses($event);
    }
}
