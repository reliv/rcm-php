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

namespace RcmTest\Service;

use Rcm\Http\Response;
use Rcm\Service\ResponseHandler;
use Zend\Console\Request;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\ResponseSender\SendResponseEvent;

require_once __DIR__ . '/../../../autoload.php';


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
 *
 */
class ResponseHandlerTest extends \PHPUnit_Framework_TestCase
{

    /** @var \Rcm\Service\ResponseHandler */
    protected $responseHandler;

    protected $mockSender;

    /** @var \Zend\Mvc\ResponseSender\SendResponseEvent */
    protected $returnedEvent;

    protected $sendHeadersCounter = 0;

    protected $request;

    /**
     * Setup Method for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->returnedEvent = null;
        $this->sendHeadersCounter = 0;
    }

    /**
     * Test the constructor
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::__construct
     */
    public function testConstructor()
    {
        $responseHandler = $this->getResponseHandler();
        $this->assertInstanceOf(
            '\Rcm\Service\ResponseHandler',
            $responseHandler
        );
    }

    /**
     * Test Processing the Response Object
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::processResponse
     */
    public function testProcessResponse()
    {
        $response = new Response();
        $response->setStatusCode(404);

        $responseHandler = $this->getResponseHandler();
        $responseHandler->processResponse($response);

        /** @var \Zend\Http\Response $returnedResponse */
        $returnedResponse = $this->returnedEvent->getResponse();

        $this->assertTrue($this->returnedEvent instanceof SendResponseEvent);
        $this->assertEquals(404, $returnedResponse->getStatusCode());
        $this->assertEquals(1, $this->sendHeadersCounter);
    }

    /**
     * Test Processing the Response Object
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::processResponse
     */
    public function testProcessResponseWithZendResponseObject()
    {
        $response = new HttpResponse();
        $response->setStatusCode(404);

        $responseHandler = $this->getResponseHandler();
        $responseHandler->processResponse($response);

        $this->assertNull($this->returnedEvent);
        $this->assertEquals(0, $this->sendHeadersCounter);
    }

    /**
     * Test Processing the Response Object
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::handleResponse
     */
    public function testHandleResponse()
    {
        $response = new Response();
        $response->setStatusCode(404);

        $responseHandler = $this->getResponseHandler();

        $reflectionMethod = new \ReflectionMethod(
            $responseHandler,
            'handleResponse'
        );

        $reflectionMethod->setAccessible(true);

        $return = $reflectionMethod->invoke($responseHandler, $response);

        $this->assertTrue($return instanceof Response);
        $this->assertEquals($response, $return);
    }

    /**
     * Test Processing the Response Object with 401 response
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::handleResponse
     */
    public function testHandleResponseWith401()
    {
        $response = new Response();
        $response->setStatusCode(401);

        $responseHandler = $this->getResponseHandler();

        $reflectionMethod = new \ReflectionMethod(
            $responseHandler,
            'handleResponse'
        );

        $reflectionMethod->setAccessible(true);

        /** @var \Rcm\Http\Response $return */
        $return = $reflectionMethod->invoke($responseHandler, $response);

        $this->assertTrue($return instanceof Response);
        $this->assertNotEquals($response, $return);
        $this->assertEquals(302, $return->getStatusCode());
        $this->assertContains(
            'Location: /login?redirect=%2Fsome-page',
            $return->getHeaders()
        );
    }

    /**
     * Test Processing Not Authorized Method
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::processNotAuthorized
     */
    public function testProcessNotAuthorized()
    {
        $responseHandler = $this->getResponseHandler();

        $reflectionMethod = new \ReflectionMethod(
            $responseHandler,
            'processNotAuthorized'
        );

        $reflectionMethod->setAccessible(true);

        /** @var \Rcm\Http\Response $return */
        $return = $reflectionMethod->invoke($responseHandler);

        $this->assertTrue($return instanceof Response);
        $this->assertEquals(302, $return->getStatusCode());
        $this->assertContains(
            'Location: /login?redirect=%2Fsome-page',
            $return->getHeaders()
        );
    }

    /**
     * Test Render Response
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::renderResponse
     */
    public function testRenderResponse()
    {
        $response = new Response();
        $response->setStatusCode(404);

        $responseHandler = $this->getResponseHandler();

        $reflectionMethod = new \ReflectionMethod(
            $responseHandler,
            'renderResponse'
        );

        $reflectionMethod->setAccessible(true);

        /** @var \Rcm\Http\Response $return */
        $reflectionMethod->invoke($responseHandler, $response);

        /** @var \Zend\Http\Response $returnedResponse */
        $returnedResponse = $this->returnedEvent->getResponse();

        $this->assertTrue($this->returnedEvent instanceof SendResponseEvent);
        $this->assertEquals(404, $returnedResponse->getStatusCode());
        $this->assertEquals(1, $this->sendHeadersCounter);
    }

    /**
     * Test Set Terminate
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::setTerminate
     */
    public function testSetTerminate()
    {
        $responseHandler = $this->getResponseHandler();
        $responseHandler->setTerminate(true);

        $reflectionProperty = new \ReflectionProperty(
            $responseHandler,
            'terminate'
        );

        $reflectionProperty->setAccessible(true);
        $value = $reflectionProperty->getValue($responseHandler);

        $this->assertTrue($value);

        $responseHandler->setTerminate(false);

        $value = $reflectionProperty->getValue($responseHandler);
        $this->assertFalse($value);
    }

    /**
     * Test Get Request using Rcm Http Request
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::getRequest
     */
    public function testGetRequest()
    {
        $responseHandler = $this->getResponseHandler();

        $return = $responseHandler->getRequest();

        $this->assertEquals($this->request, $return);
    }

    /**
     * Test Get Request using Zend Cli Request
     *
     * @return void
     *
     * @covers \Rcm\Service\ResponseHandler::getRequest
     */
    public function testGetRequestWithZendCliRequest()
    {
        $request = new Request();
        $responseHandler = $this->getResponseHandler($request);

        $return = $responseHandler->getRequest();

        $this->assertNull($return);
    }

    /**
     * Get Response Handler object to test
     *
     * @param null|\Zend\Http\PhpEnvironment\Request $request     Zend Request Object
     * @param null|\Rcm\Service\SiteManager          $siteManager Rcm Site Manager
     *
     * @return ResponseHandler
     */
    protected function getResponseHandler($request = null, $siteManager = null)
    {
        if (!$request) {
            $request = $this->getMockBuilder(
                '\Zend\Http\PhpEnvironment\Request'
            )
                ->disableOriginalConstructor()
                ->getMock();

            $request->expects($this->any())
                ->method('getServer')
                ->will($this->returnValue('/some-page'));
        }

        if (!$siteManager) {
            $siteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
                ->disableOriginalConstructor()
                ->getMock();

            $siteManager->expects($this->any())
                ->method('getCurrentSiteLoginPage')
                ->will($this->returnValue('/login'));
        }

        $mockResponseSender = $this->getMockResponseSender();

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        /** @var \Rcm\Service\SiteManager $siteManager */
        /** @var \Zend\Mvc\ResponseSender\HttpResponseSender $mockResponseSender */
        $responseHandler = new ResponseHandler(
            $request,
            $siteManager,
            $mockResponseSender
        );
        $responseHandler->setTerminate(false);

        $this->request = $request;

        return $responseHandler;
    }

    /**
     * Get a mock response sender for testing
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockResponseSender()
    {
        $this->mockSender = $this
            ->getMockBuilder('\Zend\Mvc\ResponseSender\HttpResponseSender')
            ->setMethods(array('sendHeaders'))
            ->getMock();

        $this->mockSender->expects($this->any())
            ->method('sendHeaders')
            ->will(
                $this->returnCallback(array($this, 'responseSenderCallback'))
            );

        return $this->mockSender;
    }

    /**
     * Response Sender Callback for Mock.
     *
     * @param \Zend\Mvc\ResponseSender\SendResponseEvent $event Passed in via method
     *                                                          call.
     *
     * @return mixed
     */
    public function responseSenderCallback($event)
    {
        $this->returnedEvent = $event;
        $this->sendHeadersCounter++;
        return $this->mockSender;
    }
}
