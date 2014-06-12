<?php
/**
 * Unit Test for the Route Listener Event
 *
 * This file contains the unit test for Route Listener Event
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

use Rcm\EventListener\RouteListener;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Parameters;

/**
 * Unit Test for Route Listener Event
 *
 * Unit Test for Route Listener Event
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RouteListenerTest extends \PHPUnit_Framework_TestCase
{

    /** @var \Rcm\EventListener\RouteListener */
    protected $routeListener;

    protected $domains;

    protected $redirects;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->domains = array(
            'reliv.com' => array (
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'www.reliv.com' => array (
                'domain' => 'local.reliv.com',
                'primaryDomain' => 'reliv.com',
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
        );

        $this->redirects = array(
            'reliv.com/requestOne' => array (
                    'requestUrl' => 'reliv.com/requestOne',
                    'redirectUrl' => 'reliv.com/redirectOne',
            ),

            'reliv.com/requestTwo' => array (
                'requestUrl' => 'reliv.com/requestTwo',
                'redirectUrl' => 'reliv.com/redirectTwo',
            ),
        );

        $mockDomainManager = $this
            ->getMockBuilder('\Rcm\Service\DomainManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockDomainManager->expects($this->any())
            ->method('getActiveDomainList')
            ->will($this->returnValue($this->domains));

        $mockRedirectManager = $this
            ->getMockBuilder('\Rcm\Service\RedirectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirectManager->expects($this->any())
            ->method('getRedirectList')
            ->will($this->returnValue($this->redirects));

        /** @var \Rcm\Service\DomainManager   $mockDomainManager */
        /** @var \Rcm\Service\RedirectManager $mockRedirectManager */
        $this->routeListener = new RouteListener(
            $mockDomainManager,
            $mockRedirectManager
        );
    }

    /**
     * Test Check Domain
     *
     * @return void
     *
     * @covers \Rcm\EventListener\RouteListener
     */
    public function testCheckDomain()
    {
        $serverParams = new Parameters(
            array(
                'HTTP_HOST' => 'reliv.com'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $actual = $this->routeListener->checkDomain($event);

        $this->assertFalse($actual instanceof Response);
        $this->assertEmpty($actual);
    }

    /**
     * Test Check Domain Redirects To Primary
     *
     * @return void
     *
     * @covers \Rcm\EventListener\RouteListener
     */
    public function testCheckDomainRedirectsToPrimary()
    {
        $serverParams = new Parameters(
            array(
                'HTTP_HOST' => 'www.reliv.com'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $actual = $this->routeListener->checkDomain($event);

        $this->assertTrue($actual instanceof Response);
    }

    /**
     * Test Check Domain Return 404 if not found.
     *
     * @return void
     *
     * @covers \Rcm\EventListener\RouteListener
     */
    public function testCheckDomainReturnsNotFound()
    {
        $serverParams = new Parameters(
            array(
                'HTTP_HOST' => 'not.found.com'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $actual = $this->routeListener->checkDomain($event);

        $this->assertTrue($actual instanceof Response);

        $responseCode = $actual->getStatusCode();

        $this->assertEquals(404, $responseCode);
    }

    /**
     * Test Check Redirects Return 302 if found.
     *
     * @return void
     *
     * @covers \Rcm\EventListener\RouteListener
     */
    public function testCheckRedirects()
    {
        $serverParams = new Parameters(
            array(
                'HTTP_HOST'   => 'reliv.com',
                'REQUEST_URI' => '/requestOne'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $expectedLocation
            = 'Location: //'.$this->redirects['reliv.com/requestOne']['redirectUrl'];

        $actual = $this->routeListener->checkRedirect($event);

        $this->assertTrue($actual instanceof Response);

        $responseCode = $actual->getStatusCode();

        $this->assertEquals(302, $responseCode);

        $redirectHeader = $actual->getHeaders()->get('Location')->toString();

        $this->assertEquals($expectedLocation, $redirectHeader);
    }

    /**
     * Test Check Redirects Redirect Not Found
     *
     * @return void
     *
     * @covers \Rcm\EventListener\RouteListener
     */
    public function testCheckRedirectsNoRedirectFound()
    {
        $serverParams = new Parameters(
            array(
                'HTTP_HOST'   => 'reliv.com',
                'REQUEST_URI' => '/no-redirect'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $actual = $this->routeListener->checkRedirect($event);

        $this->assertFalse($actual instanceof Response);
        $this->assertEmpty($actual);
    }


}