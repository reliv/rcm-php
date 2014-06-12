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
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Rcm\Exception\ContainerNotFoundException;
use Zend\Server\Reflection\ReflectionClass;
use Zend\Server\Reflection\ReflectionMethod;

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

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUserServicePlugin;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageManager;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->mockUserServicePlugin = $this
            ->getMockBuilder('\RcmUser\Controller\Plugin\RcmUserIsAllowed')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPageManager->expects($this->any())
            ->method('getRevisionInfo')
            ->will($this->returnCallback(array($this, 'pageManagerMockCallback')));

        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager->expects($this->any())
            ->method('getLayout')
            ->will($this->returnCallback(array($this, 'layoutManagerMockCallback')));

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


        /** @var \Rcm\Service\PageManager $mockPageManager */
        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $this->controller = new IndexController(
            $this->mockPageManager,
            $mockLayoutManager,
            1
        );

        $this->controller->getPluginManager()
            ->setService('rcmUserIsAllowed', $this->mockUserServicePlugin);

        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
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
        /** @var \Rcm\Service\PageManager $mockPageManager */
        $mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $controller = new IndexController($mockPageManager, $mockLayoutManager, 1);

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

        $result   = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->pageData, $result->pageInfo);
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

        $result   = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->pageData, $result->pageInfo);
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
    public function testIndexActionHomePageRedirectWhenUserNotAllowedForRevisions()
    {
        $this->pageData = $this->getPageData(42, 'my-test', 443, 'n');

        $this->routeMatch->setParam('action', 'index');
        $this->routeMatch->setParam('page', 'my-test');
        $this->routeMatch->setParam('pageType', 'z');
        $this->routeMatch->setParam('revision', 443);

        $response = $this->controller->getResponse();
        $response->getHeaders()->addHeaderLine('Location', '/my-test');
        $response->setStatusCode(302);

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

        $result   = $this->controller->dispatch($this->request);

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

        $result   = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals($this->pageData, $result->pageInfo);
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

        $result   = $this->controller->dispatch($this->request);

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

        $result   = $this->controller->dispatch($this->request);

        /** @var \Zend\Http\Response $response */
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->pageData, $result->pageInfo);
        $this->assertEquals('my-test', $this->controller->pageName);
        $this->assertEquals('z', $this->controller->pageType);
        $this->assertEquals(443, $this->controller->pageRevisionId);
        $this->assertEquals(
            'layout/'.$this->layoutOverride,
            $this->controller->layout()->getTemplate()
        );
    }

    /**
     * Test the index controllers Should Show Revisions method when
     * user is allowed to edit page.
     *
     * @return null
     * @covers Rcm\Controller\IndexController::shouldShowRevisions
     */
    public function testShouldShowRevisionsWithPageEdit()
    {
        $siteId = 1;
        $pageName = 'my-test';

        $resource = 'sites.'.$siteId.'.pages.'.$pageName;
        $permission = 'edit';
        $provider = 'Rcm\Acl\ResourceProvider';

        $this->mockUserServicePlugin
            ->expects($this->once())
            ->method('__invoke')
            ->with(
                $this->equalTo($resource),
                $this->equalTo($permission),
                $this->equalTo($provider)
            )
            ->will($this->returnValue(true));

        $reflectedController = new \ReflectionClass($this->controller);
        $reflectedShowRev = $reflectedController->getMethod('shouldShowRevisions');
        $reflectedShowRev->setAccessible(true);

        $reflectedSiteId = $reflectedController->getProperty('siteId');
        $reflectedSiteId->setAccessible(true);
        $reflectedSiteId->setValue($this->controller, $siteId);

        $reflectedPageName = $reflectedController->getProperty('pageName');
        $reflectedPageName->setAccessible(true);
        $reflectedPageName->setValue($this->controller, $pageName);

        $result = $reflectedShowRev->invoke($this->controller);

        $this->assertTrue($result);
    }

    /**
     * Test the index controllers Should Show Revisions method when
     * user is allowed to edit page.
     *
     * @return null
     * @covers Rcm\Controller\IndexController::shouldShowRevisions
     */
    public function testShouldShowRevisionsWithPageApproval()
    {
        $siteId = 1;
        $pageName = 'my-test';

        $resource = 'sites.'.$siteId.'.pages.'.$pageName;
        $provider = 'Rcm\Acl\ResourceProvider';

        $map = array(
            array($resource, 'edit', $provider, false),
            array($resource, 'approve', $provider, true)
        );

        $this->mockUserServicePlugin
            ->expects($this->exactly(2))
            ->method('__invoke')
            ->will(
                $this->returnValueMap($map)
            );

        $reflectedController = new \ReflectionClass($this->controller);
        $reflectedShowRev = $reflectedController->getMethod('shouldShowRevisions');
        $reflectedShowRev->setAccessible(true);

        $reflectedSiteId = $reflectedController->getProperty('siteId');
        $reflectedSiteId->setAccessible(true);
        $reflectedSiteId->setValue($this->controller, $siteId);

        $reflectedPageName = $reflectedController->getProperty('pageName');
        $reflectedPageName->setAccessible(true);
        $reflectedPageName->setValue($this->controller, $pageName);

        $result = $reflectedShowRev->invoke($this->controller);

        $this->assertTrue($result);
    }

    /**
     * Test the index controllers Should Show Revisions method when
     * user is allowed to edit page.
     *
     * @return null
     * @covers Rcm\Controller\IndexController::shouldShowRevisions
     */
    public function testShouldShowRevisionsWithPageRevisions()
    {
        $siteId = 1;
        $pageName = 'my-test';

        $resource = 'sites.'.$siteId.'.pages.'.$pageName;
        $provider = 'Rcm\Acl\ResourceProvider';

        $map = array(
            array($resource, 'edit', $provider, false),
            array($resource, 'approve', $provider, false),
            array($resource, 'revisions', $provider, true)
        );

        $this->mockUserServicePlugin
            ->expects($this->exactly(3))
            ->method('__invoke')
            ->will(
                $this->returnValueMap($map)
            );

        $reflectedController = new \ReflectionClass($this->controller);
        $reflectedShowRev = $reflectedController->getMethod('shouldShowRevisions');
        $reflectedShowRev->setAccessible(true);

        $reflectedSiteId = $reflectedController->getProperty('siteId');
        $reflectedSiteId->setAccessible(true);
        $reflectedSiteId->setValue($this->controller, $siteId);

        $reflectedPageName = $reflectedController->getProperty('pageName');
        $reflectedPageName->setAccessible(true);
        $reflectedPageName->setValue($this->controller, $pageName);

        $result = $reflectedShowRev->invoke($this->controller);

        $this->assertTrue($result);
    }

    /**
     * Test the index controllers Should Show Revisions method when
     * user is allowed to edit page.
     *
     * @return null
     * @covers Rcm\Controller\IndexController::shouldShowRevisions
     */
    public function testShouldShowRevisionsWithNoPermissions()
    {
        $siteId = 1;
        $pageName = 'my-test';

        $resource = 'sites.'.$siteId.'.pages.'.$pageName;
        $provider = 'Rcm\Acl\ResourceProvider';

        $map = array(
            array($resource, 'edit', $provider, false),
            array($resource, 'approve', $provider, false),
            array($resource, 'revisions', $provider, false)
        );

        $this->mockUserServicePlugin
            ->expects($this->exactly(3))
            ->method('__invoke')
            ->will(
                $this->returnValueMap($map)
            );

        $reflectedController = new \ReflectionClass($this->controller);
        $reflectedShowRev = $reflectedController->getMethod('shouldShowRevisions');
        $reflectedShowRev->setAccessible(true);

        $reflectedSiteId = $reflectedController->getProperty('siteId');
        $reflectedSiteId->setAccessible(true);
        $reflectedSiteId->setValue($this->controller, $siteId);

        $reflectedPageName = $reflectedController->getProperty('pageName');
        $reflectedPageName->setAccessible(true);
        $reflectedPageName->setValue($this->controller, $pageName);

        $result = $reflectedShowRev->invoke($this->controller);

        $this->assertFalse($result);
    }

    /**
     * Callback for Page Manager mock
     *
     * @return mixed
     * @throws \Rcm\Exception\ContainerNotFoundException
     */
    public function pageManagerMockCallback()
    {
        if ($this->skipCounter > 0) {
            $this->skipCounter--;
            throw new ContainerNotFoundException('Page Not Found');
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
     * Get Test Page Data for PageManager Mocks
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
        $pageType='n',
        $siteLayoutOverride = null
    ) {
        return array (
            'pageId' => $pageId,
            'name' => $pageName,
            'author' => 'Test Script',
            'createdDate' => new \DateTime(),
            'lastPublished' => new \DateTime(),
            'pageLayout' => null,
            'siteLayoutOverride' => $siteLayoutOverride,
            'pageTitle' => 'Test Page',
            'description' => 'Test Page',
            'keywords' => 'My Test Page',
            'pageType' => $pageType,
            'revision' =>
                array (
                    'revisionId' => $revisionId,
                    'author' => 'Test Script',
                    'createdDate' => new \DateTime(),
                    'published' => true,
                    'md5' => '664af24be398368b59bbf3c6d2b3459a',
                    'staged' => false,
                    'pluginInstances' =>
                        array (
                            0 => array (
                                'pluginWrapperId' => 227075,
                                'layoutContainer' => 4,
                                'renderOrder' => 0,
                                'height' => 391,
                                'width' => 287,
                                'divFloat' => 'left',
                                'instance' => array (
                                    'pluginInstanceId' => 19889,
                                    'plugin' => 'RcmHtmlArea',
                                    'siteWide' => false,
                                    'displayName' => 'Rich Content Area',
                                    'md5' => 'f5672a3279ac62664a3d3920aff7936a',
                                    'previousEntity' => 11817,
                                    'renderedData' => array (
                                        'html' => '<a></a>',
                                        'css' => array (),
                                        'js' => array (),
                                        'editJs' => '',
                                        'editCss' => '',
                                        'displayName' => 'Rich Content Area',
                                        'tooltip' => 'My ToolTip',
                                        'icon' => '',
                                        'siteWide' => false,
                                        'md5' => 'f5672a3279ac62664a3d3920aff7936a',
                                        'fromCache' => false,
                                        'canCache' => true,
                                        'pluginName' => 'RcmHtmlArea',
                                        'pluginInstanceId' => 19889,
                                    ),
                                ),
                            ),
                        ),
                ),
            'siteId' => 1,
            'currentRevisionId' => $revisionId,
            'stagedRevisionId' => $revisionId,
        );
    }
}