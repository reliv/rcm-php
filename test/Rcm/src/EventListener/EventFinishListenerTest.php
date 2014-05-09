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
use Zend\View\Model\ViewModel;

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
     * Test Check For Not Authorized
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener
     */
    public function testCheckForNotAuthorized()
    {

        $mockSiteManager = $this->getMockBuilder('Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteLoginPage')
            ->will(
                $this->returnValue(
                    '/login'
                )
            );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        $listener = new EventFinishListener(
            $mockSiteManager
        );

        $event = new MvcEvent();

        $notAuthorizedResponse = new Response();
        $notAuthorizedResponse->setStatusCode(401);

        $requestMock = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock->expects($this->any())
            ->method('getServer')
            ->will($this->returnValue('/myPage'));

        $event->setRequest($requestMock);
        $event->setResult($notAuthorizedResponse);

        $listener->checkForNotAuthorized($event);

        /** @var \Zend\Http\Response $newResponse */
        $newResponse = $event->getResponse();

        //Check not an RCM http response
        $this->assertNotInstanceOf('\Rcm\Http\Response', $newResponse);

        //Check status code
        $this->assertEquals('302', $newResponse->getStatusCode());

        //Check Header
        $expectedHeader = 'Location: /login?redirect=%2FmyPage';
        $headers = $newResponse->getHeaders()->toString();

        $this->assertContains($expectedHeader, $headers);
    }

    /**
     * Test Check For Not Authorized when a normal view model is returned
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener
     */
    public function testCheckForNotAuthorizedWithNormalViewModelInResult()
    {

        $mockSiteManager = $this->getMockBuilder('Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteLoginPage')
            ->will(
                $this->returnValue(
                    '/login'
                )
            );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        $listener = new EventFinishListener(
            $mockSiteManager
        );

        $event = new MvcEvent();

        $eventResult = new ViewModel();

        $event->setResult($eventResult);
        $event->setResponse(new Response());

        $listener->checkForNotAuthorized($event);

        /** @var \Zend\Http\Response $newResponse */
        $newResponse = $event->getResponse();

        //Check status code
        $this->assertNotEquals('302', $newResponse->getStatusCode());
    }

    /**
     * Test Check For Not Authorized when response object is not a 401
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener
     */
    public function testCheckForNotAuthorizedWhenResponseIsNot401()
    {

        $mockSiteManager = $this->getMockBuilder('Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteLoginPage')
            ->will(
                $this->returnValue(
                    '/login'
                )
            );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        $listener = new EventFinishListener(
            $mockSiteManager
        );

        $event = new MvcEvent();

        $event->setResult(new Response());
        $event->setResponse(new Response());

        $listener->checkForNotAuthorized($event);

        /** @var \Zend\Http\Response $newResponse */
        $newResponse = $event->getResponse();

        //Check status code
        $this->assertNotEquals('302', $newResponse->getStatusCode());
    }

    /**
     * Test Check For Not Authorized skips 401 zend response objects
     *
     * @return void
     *
     * @covers \Rcm\EventListener\EventFinishListener
     */
    public function testCheckForNotAuthorizedWhenResponseSkipsZendResponse()
    {

        $mockSiteManager = $this->getMockBuilder('Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteLoginPage')
            ->will(
                $this->returnValue(
                    '/login'
                )
            );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        $listener = new EventFinishListener(
            $mockSiteManager
        );

        $event = new MvcEvent();

        $notAuthorizedResponse = new ZendHttpResponse();
        $notAuthorizedResponse->setStatusCode(401);


        $event->setResult($notAuthorizedResponse);
        $event->setResponse($notAuthorizedResponse);

        $listener->checkForNotAuthorized($event);

        /** @var \Zend\Http\Response $newResponse */
        $newResponse = $event->getResponse();

        //Check status code
        $this->assertNotEquals('302', $newResponse->getStatusCode());
    }
}
