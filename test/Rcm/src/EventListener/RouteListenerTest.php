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

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use Rcm\EventListener\RouteListener;
use RcmTest\Base\BaseTestCase;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

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
class RouteListenerTest extends BaseTestCase
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
        $this->addModule('Rcm');
        parent::setUp();

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
            ->method('getDomainList')
            ->will($this->returnValue($this->domains));

        $mockDomainManager->expects($this->any())
            ->method('getRedirectList')
            ->will($this->returnValue($this->redirects));

        /** @var \Rcm\Service\DomainManager $mockDomainManager */
        $this->routeListener = new RouteListener($mockDomainManager);
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
        $event = new MvcEvent();

        $_SERVER['HTTP_HOST'] = 'reliv.com';

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
        $event = new MvcEvent();

        $_SERVER['HTTP_HOST'] = 'www.reliv.com';

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
        $event = new MvcEvent();

        $_SERVER['HTTP_HOST'] = 'not.found.com';

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
        $event = new MvcEvent();

        $_SERVER['HTTP_HOST'] = 'reliv.com';
        $_SERVER['REQUEST_URI'] = '/requestOne';

        $expectedLocation
            = 'Location: //'.$this->redirects['reliv.com/requestOne']['redirectUrl'];

        $actual = $this->routeListener->checkRedirect($event);

        $this->assertTrue($actual instanceof Response);

        $responseCode = $actual->getStatusCode();

        $this->assertEquals(302, $responseCode);

        $redirectLocationHeader = $actual->getHeaders()->get('Location')->toString();

        $this->assertEquals($expectedLocation, $redirectLocationHeader);
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
        $event = new MvcEvent();

        $_SERVER['HTTP_HOST'] = 'not.found.com';
        $_SERVER['REQUEST_URI'] = '/no-redirect';

        $actual = $this->routeListener->checkRedirect($event);

        $this->assertFalse($actual instanceof Response);
        $this->assertEmpty($actual);
    }


}