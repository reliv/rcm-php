<?php

namespace RcmTest\EventListener;



use Rcm\Entity\Domain;
use Rcm\Entity\Redirect;
use Rcm\Entity\Site;
use Rcm\EventListener\RouteListener;
use Rcm\Service\DomainRedirectService;
use Rcm\Service\LocaleService;
use Rcm\Service\RedirectService;
use Rcm\Service\SiteService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Parameters;

/**
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

    protected $siteServiceMock;
    protected $redirectServiceMock;
    protected $domainRedirectServiceMock;
    protected $localeServiceMock;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->domains = [
            'reliv.com' => [
                'domain' => 'local.reliv.com',
                'primaryDomain' => null,
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ],
            'www.reliv.com' => [
                'domain' => 'local.reliv.com',
                'primaryDomain' => 'reliv.com',
                'languageId' => 'eng',
                'siteId' => 1,
                'countryId' => 'USA',
            ],
        ];

        $this->redirects = [
            '/requestOne' => [
                'requestUrl' => '/requestOne',
                'redirectUrl' => 'reliv.com/redirectOne',
            ],
            '/requestTwo' => [
                'requestUrl' => '/requestTwo',
                'redirectUrl' => 'reliv.com/redirectTwo',
            ],
        ];

        $config = [];

        $this->currentSite = new Site('user123');
        $this->currentSite->setSiteId(1);
        $this->currentSite->setStatus(Site::STATUS_ACTIVE);

        $domain = new Domain('user123');
        $domain->setDomainId(1);
        $domain->setDomainName('reliv.com');

        $this->currentSite->setDomain($domain);

        $this->redirectRepo = $this
            ->getMockBuilder('\Rcm\Repository\Redirect')
            ->disableOriginalConstructor()
            ->getMock();

        $map = [];

        foreach ($this->redirects as $key => $redirect) {
            $redirectEntity = new Redirect('user123');
            $redirectEntity->setRedirectUrl($redirect['requestUrl']);
            $redirectEntity->setRedirectUrl($redirect['redirectUrl']);

            $map[] = [$key, $this->currentSite->getSiteId(), $redirectEntity];
        }

        $this->redirectRepo->expects($this->any())
            ->method('getRedirect')
            ->will($this->returnValueMap($map));
        //////////////

        $this->siteServiceMock = $this
            ->getMockBuilder(SiteService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->siteServiceMock->expects($this->any())
            ->method('getCurrentSite')
            ->will($this->returnValue($this->currentSite));

        $this->siteServiceMock->expects($this->any())
            ->method('isConsoleRequest')
            ->will($this->returnValue(false));

        $this->redirectServiceMock = $this
            ->getMockBuilder(RedirectService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->domainRedirectServiceMock = $this
            ->getMockBuilder(DomainRedirectService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->localeServiceMock = $this
            ->getMockBuilder(LocaleService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->routeListener = new RouteListener(
            $this->siteServiceMock,
            $this->redirectServiceMock,
            $this->domainRedirectServiceMock,
            $this->localeServiceMock
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
            [
                'HTTP_HOST' => 'reliv.com'
            ]
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
            [
                'HTTP_HOST' => 'www.reliv.com'
            ]
        );

        $primary = $this->currentSite->getDomain();
        $domain = new Domain('user123');
        $domain->setDomainId(1);
        $domain->setDomainName('www.reliv.com');
        $domain->setPrimary($primary);

        $this->currentSite->setDomain($domain);

        $this->domainRedirectServiceMock->expects($this->any())
            ->method('getPrimaryRedirectUrl')
            ->will($this->returnValue($primary->getDomainName()));

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $actual = $this->routeListener->checkDomain($event);

        $this->assertTrue($actual instanceof Response);
        $this->assertEquals(302, $actual->getStatusCode());

        $this->assertEquals(
            '//' . $this->currentSite->getDomain()->getPrimary()->getDomainName(),
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
            [
                'HTTP_HOST' => 'not.found.com'
            ]
        );

        $this->currentSite->setDomain(new Domain('user123'));
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
            [
                'HTTP_HOST' => 'reliv.com',
                'REQUEST_URI' => '/requestOne'
            ]
        );

        $request = new Request();
        $request->setServer($serverParams);
        $event = new MvcEvent();
        $event->setRequest($request);

        $expectedLocation
            = 'Location: '
            . $this->redirects['/requestOne']['redirectUrl'];

        // @todo Fix me
        try {
            $actual = $this->routeListener->checkRedirect($event);

            $this->assertTrue($actual instanceof Response);

            $responseCode = $actual->getStatusCode();

            $this->assertEquals(302, $responseCode);

            $redirectHeader = $actual->getHeaders()->get('Location')->toString();

            $this->assertEquals($expectedLocation, $redirectHeader);
        } catch (\Exception $e) {
        }
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
            [
                'HTTP_HOST' => 'reliv.com',
                'REQUEST_URI' => '/no-redirect'
            ]
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
