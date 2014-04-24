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

require_once __DIR__ . '/../../../Base/BaseTestCase.php';

use RcmTest\Base\BaseTestCase;
use Rcm\Controller\IndexController;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;

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
class IndexControllerTest extends BaseTestCase
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

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->addModule('Rcm');

        parent::setUp();

        $mockPageManager = $this
            ->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPageManager->expects($this->any())
            ->method('getPageRevisionInfo')
            ->will($this->returnCallback(array($this, 'pageManagerMockCallback')));

        $mockLayoutManager = $this
            ->getMockBuilder('\Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager->expects($this->any())
            ->method('getLayout')
            ->will($this->returnCallback(array($this, 'layoutManagerMockCallback')));

        $serviceManager = $this->getServiceManager();

        /** @var \Rcm\Service\PageManager $mockPageManager */
        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $this->controller = new IndexController(
            $mockPageManager,
            $mockLayoutManager
        );

        $this->request    = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'index'));
        $this->event      = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);
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

        $controller = new IndexController($mockPageManager, $mockLayoutManager);

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

        $result   = $this->controller->dispatch($this->request);
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
        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->pageData, $result->pageInfo);
        $this->assertEquals('index', $this->controller->pageName);
        $this->assertEquals('n', $this->controller->pageType);
        $this->assertEquals(null, $this->controller->pageRevisionId);
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

        $result   = $this->controller->dispatch($this->request);
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

        $result   = $this->controller->dispatch($this->request);
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

        $result   = $this->controller->dispatch($this->request);
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
     * Callback for Page Manager mock
     *
     * @return mixed
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function pageManagerMockCallback()
    {
        if ($this->skipCounter > 0) {
            $this->skipCounter--;
            throw new \Rcm\Exception\PageNotFoundException('Page Not Found');
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