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

use Rcm\Entity\Domain;
use Rcm\Entity\Redirect;
use Rcm\Entity\Site;
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

    protected $redirectRepo;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->domains = array(
            'reliv.com' => array(
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
            'www.reliv.com' => array(
                'domain' => 'local.reliv.com',
                'primaryDomain' => 'reliv.com',
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ),
        );

        $this->redirects = array(
            '/requestOne' => array(
                'requestUrl' => '/requestOne',
                'redirectUrl' => 'reliv.com/redirectOne',
            ),
            '/requestTwo' => array(
                'requestUrl' => '/requestTwo',
                'redirectUrl' => 'reliv.com/redirectTwo',
            ),
        );

        $config = array();

        $this->currentSite = new Site();
        $this->currentSite->setSiteId(1);
        $this->currentSite->setStatus('A');

        $domain = new Domain();
        $domain->setDomainId(1);
        $domain->setDomainName('reliv.com');

        $this->currentSite->setDomain($domain);

        $this->redirectRepo = $this
            ->getMockBuilder('\Rcm\Repository\Redirect')
            ->disableOriginalConstructor()
            ->getMock();

        $map = array();

        foreach ($this->redirects as $key => $redirect) {
            $redirectEntity = new Redirect();
            $redirectEntity->setRedirectUrl($redirect['requestUrl']);
            $redirectEntity->setRedirectUrl($redirect['redirectUrl']);

            $map[] = array($key, $this->currentSite->getSiteId(), $redirectEntity);
        }

        $this->redirectRepo->expects($this->any())
            ->method('getRedirect')
            ->will($this->returnValueMap($map));

        $this->routeListener = new RouteListener(
            $this->currentSite,
            $this->redirectRepo,
            $config
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
        $this->assertEquals(302, $actual->getStatusCode());

        $this->assertEquals(
            '//'.$this->currentSite->getDomain()->getDomainName(),
            $actual->getHeaders()->get('Location')->getFieldValue()
        );
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

        $this->currentSite->setDomain(new Domain());
        $this->currentSite->setSiteId(null);

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
                'HTTP_HOST' => 'reliv.com',
                'REQUEST_URI' => '/requestOne'
            )
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $expectedLocation
            = 'Location: '
            . $this->redirects['/requestOne']['redirectUrl'];

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
                'HTTP_HOST' => 'reliv.com',
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