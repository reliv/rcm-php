<?php
/**
 * Unit Test for the IndexController
 *
 * This file contains the unit test for the IndexController
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
namespace RcmTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Controller\IndexController;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

/**
 * Unit Test for the IndexController
 *
 * Unit Test for the IndexController
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class IndexControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Controller\IndexController */
    protected $controller;

    /** @var \Zend\Http\Request */
    protected $request;

    /** @var \Zend\Http\Response */
    protected $response;

    /** @var \Zend\Mvc\Router\RouteMatch */
    protected $routeMatch;

    /** @var \Zend\Mvc\MvcEvent */
    protected $event;

    protected $pageData;

    protected $skipCounter = 0;

    protected $layoutOverride;

    /** @var \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUserServicePlugin;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockIsPageAllowed;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockShouldShowRevisions;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockRedirectToPage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockIsSiteAdmin;

    /** @var  \Rcm\Repository\Page */
    protected $mockPageRepo;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {

        $this->mockPageRepo = $this
            ->getMockBuilder('\Rcm\Repository\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageRepo->expects($this->any())
            ->method('getPageByName')
            ->will(
                $this->returnCallback(array($this, 'pageRepoMockCallback'))
            );

        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager->expects($this->any())
            ->method('getSiteLayout')
            ->will(
                $this->returnCallback(array($this, 'layoutManagerMockCallback'))
            );

        $this->mockUserServicePlugin = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\RcmIsAllowed')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIsPageAllowed = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\RcmIsPageAllowed')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockShouldShowRevisions = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\ShouldShowRevisions')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockRedirectToPage = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\RedirectToPage')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIsSiteAdmin = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\IsSiteAdmin')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currentSite = new Site();
        $this->currentSite->setSiteId(1);


        $config = array(
            'contentManager' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm[/:page][/:revision]',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    )
                ),
            ),
            'contentManagerWithPageType' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm/:pageType/:page[/:revision]',
                    'constraints' => array(
                        'pageType' => '[a-z]',
                    ),
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    )
                ),
            ),
        );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $this->controller = new IndexController(
            $mockLayoutManager,
            $this->currentSite,
            $this->mockPageRepo
        );

        $this->controller->getPluginManager()
            ->setService('rcmIsAllowed', $this->mockUserServicePlugin)
            ->setService('shouldShowRevisions', $this->mockShouldShowRevisions)
            ->setService('redirectToPage', $this->mockRedirectToPage)
            ->setService('rcmIsSiteAdmin', $this->mockIsSiteAdmin)
            ->setService('rcmIsPageAllowed', $this->mockIsPageAllowed);

        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event = new MvcEvent();
        $routerConfig = $config;
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    /**
     * Test the constructor is working
     *
     * @return void
     * @covers Rcm\Controller\IndexController
     */
    public function testConstructor()
    {
        /** @var \Rcm\Repository\Page $mockPageRepo */
        $mockPageRepo = $this
            ->getMockBuilder('\Rcm\Repository\Page')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $currentSite = new Site();
        $currentSite->setSiteId(1);

        $controller = new IndexController(
                $mockLayoutManager,
                $currentSite,
                $mockPageRepo
        );

        $this->assertTrue($controller instanceof IndexController);
    }

    /**
     * Test the index controller for normal functionality
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexAction()
    {
        $this->pageData = $this->getPageData(42, 'my-test', 443, 'z');

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $this->mockUserServicePlugin->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockShouldShowRevisions->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $result = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('my-test', $this->controller->pageName);
        $this->assertEquals('z', $this->controller->pageType);
        $this->assertEquals(443, $this->controller->pageRevisionId);
    }

    /**
     * Test the index controller attempts to fetch the index or home page
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexActionHomePage()
    {
        $this->pageData = $this->getPageData(42, 'my-test', 443, 'n');

        $this->routeMatch->setParam('action', 'index');

        $this->mockUserServicePlugin->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockShouldShowRevisions->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $result = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('index', $this->controller->pageName);
        $this->assertEquals('n', $this->controller->pageType);
        $this->assertEquals(null, $this->controller->pageRevisionId);
    }

    /**
     * Test the index controller attempts to fetch the index or home page with
     * revision but user is not allowed to see revisions
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexActionHomePageRedirectWhenUserNotAllowedForRevisions(
    )
    {
        $this->pageData = $this->getPageData(42, 'my-test', 443, 'z');

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $response = $this->controller->getResponse();
        $response->getHeaders()->addHeaderLine('Location', '/my-test');
        $response->setStatusCode(302);

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $mockRedirectToPage = $this
            ->getMockBuilder('\Rcm\Controller\Plugin\RedirectToPage')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirectToPage->expects($this->any())
            ->method('__invoke')
            ->with(
                $this->equalTo('my-test'),
                $this->equalTo('z')
            )
            ->will($this->returnValue($response));

        $this->controller->getPluginManager()
            ->setService('redirectToPage', $mockRedirectToPage);

        $result = $this->controller->dispatch($this->request);

        $this->assertInstanceOf('\Zend\Http\Response', $result);
        $this->assertEquals(302, $result->getStatusCode());

    }

    /**
     * Test the index controller attempts to fetch the index or home page
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexActionPageNotFoundWithCmsPageNotFoundAvailable()
    {
        $this->skipCounter = 1;
        $this->pageData = $this->getPageData(3, 'not-found', 22, 'n');

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $this->mockUserServicePlugin->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockShouldShowRevisions->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $result = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        // @todo fix me
        //$this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('not-found', $this->controller->pageName);
        $this->assertEquals('n', $this->controller->pageType);
        $this->assertEquals(null, $this->controller->pageRevisionId);
    }

    /**
     * Test the index controller attempts to fetch the index or home page
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexActionPageNotFoundWithCmsPageNotFoundMissing()
    {
        $this->skipCounter = 2;
        $this->pageData = $this->getPageData(3, 'not-found', 22, 'n');

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $this->mockUserServicePlugin->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockShouldShowRevisions->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $result = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEmpty($result->pageInfo);
    }


    /**
     * Test the index controller with Layout Template override
     *
     * @return null
     * @covers Rcm\Controller\IndexController
     */
    public function testIndexActionWithLayoutOverride()
    {
        $this->layoutOverride = 'newLayout';

        $this->pageData = $this->getPageData(
            42,
            'my-test',
            443,
            'z',
            $this->layoutOverride
        );

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $this->mockUserServicePlugin->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockShouldShowRevisions->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $this->mockIsPageAllowed->expects($this->any())
            ->method('__invoke')
            ->will($this->returnValue(true));

        $result = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('my-test', $this->controller->pageName);
        $this->assertEquals('z', $this->controller->pageType);
        $this->assertEquals(443, $this->controller->pageRevisionId);
        $this->assertEquals(
            'layout/' . $this->layoutOverride,
            $this->controller->layout()->getTemplate()
        );
    }

    /**
     * Callback for Page Manager mock
     *
     * @return mixed
     * @throws \Rcm\Exception\ContainerNotFoundException
     */
    public function pageRepoMockCallback()
    {
        if ($this->skipCounter > 0) {
            $this->skipCounter--;
            return null;
        }

        return $this->pageData;
    }

    /**
     * Callback for layout Manager mock
     *
     * @return mixed
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function layoutManagerMockCallback()
    {
        return $this->layoutOverride;
    }

    /**
     * Get Test Page Data for Page Repo Mocks
     *
     * @param integer $pageId             PageID
     * @param integer $pageName           PageName
     * @param integer $revisionId         RevisionId
     * @param string  $pageType           PageType
     * @param string  $siteLayoutOverride Layout Override
     *
     * @return array
     */
    protected function getPageData(
        $pageId,
        $pageName,
        $revisionId,
        $pageType = 'n',
        $siteLayoutOverride = null
    ) {
        $country = new Country();
        $country->setCountryName('United States');
        $country->setIso2('US');
        $country->setIso3('USA');

        $language = new Language();
        $language->setLanguageId(1);
        $language->setIso6391('en');
        $language->setIso6392b('eng');
        $language->setIso6392t('eng');

        $domain = new Domain();
        $domain->setDomainId(1);
        $domain->setDomainName('reliv.com');

        $site = new Site();
        $site->setSiteId(1);
        $site->setCountry($country);
        $site->setLanguage($language);
        $site->setLoginPage('login');
        $site->setNotFoundPage('not-found');
        $site->setDomain($domain);

        $revision = new Revision();
        $revision->setRevisionId($revisionId);
        $revision->setAuthor('Westin Shafer');
        $revision->setCreatedDate(new \DateTime());
        $revision->setPublishedDate(new \DateTime());


        $page = new Page();
        $page->setSite($site);
        $page->setName($pageName);
        $page->setPageId($pageId);
        $page->setPageType($pageType);
        $page->addRevision($revision);
        $page->setPublishedRevision($revision);
        $page->setStagedRevision($revision);
        $page->setPageId(22);
        $page->setSiteLayoutOverride($siteLayoutOverride);


        return $page;
    }
}