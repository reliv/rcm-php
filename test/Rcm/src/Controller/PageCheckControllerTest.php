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

use Rcm\Controller\PageCheckController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;

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
class PageCheckControllerTest extends \PHPUnit_Framework_TestCase
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

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPageValidator;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $config = [
            'Rcm\Api\Page\Check' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/rcm/page/check[/:pageType]/:pageId',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\PageCheckController',
                    ],
                ],
            ],
        ];

        $this->mockPageValidator = $this
            ->getMockBuilder('\Rcm\Validator\Page')
            ->disableOriginalConstructor()
            ->getMock();

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'Rcm\Validator\Page',
            $this->mockPageValidator
        );

        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        $this->controller = new PageCheckController();
        $this->controller->setServiceLocator($serviceManager);

        $this->request = new Request();
        $this->request->setMethod('GET');
        $this->routeMatch = new RouteMatch(
            ['controller' => 'Rcm\Controller\PageCheckController']
        );
        $this->event = new MvcEvent();
        $routerConfig = $config;
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
    }

    /**
     * Test Page Check Controller with Valid Page Name
     *
     * @return void
     *
     * @covers \Rcm\Controller\PageCheckController
     */
    public function testPageCheckControllerGetMethodWithValidPage()
    {
        $this->mockPageValidator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->routeMatch->setParam('pageId', 'my-test-page');
        $this->routeMatch->setParam('pageType', 'z');

        $result = $this->controller->dispatch($this->request);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);

        $response = $this->controller->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $result = $result->getVariables();

        $expected = [
            'valid' => true,
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Page Check Controller with Invalid Page Name
     *
     * @return void
     *
     * @covers \Rcm\Controller\PageCheckController
     */
    public function testPageCheckControllerGetMethodWithInvalidPage()
    {
        $this->mockPageValidator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->mockPageValidator->expects($this->any())
            ->method('getMessages')
            ->will(
                $this->returnValue(["pageName" => 'Page Name Invalid'])
            );

        $this->routeMatch->setParam('pageId', 'my invalid test page');

        /** @var \Zend\View\Model\JsonModel $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);

        $response = $this->controller->getResponse();

        $this->assertEquals(417, $response->getStatusCode());

        $result = $result->getVariables();

        $expected = [
            'valid' => false,
            'error' => [
                'pageName' => 'Page Name Invalid'
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Page Check Controller with Existing Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\PageCheckController
     */
    public function testPageCheckControllerGetMethodWithExistingPage()
    {
        $this->mockPageValidator->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->mockPageValidator->expects($this->any())
            ->method('getMessages')
            ->will($this->returnValue(["pageExists" => 'Page Exists']));

        $this->routeMatch->setParam('pageId', 'page-exists');

        /** @var \Zend\View\Model\JsonModel $result */
        $result = $this->controller->dispatch($this->request);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $result);

        $response = $this->controller->getResponse();

        $this->assertEquals(409, $response->getStatusCode());

        $result = $result->getVariables();

        $expected = [
            'valid' => false,
            'error' => [
                'pageExists' => 'Page Exists'
            ]
        ];

        $this->assertEquals($expected, $result);
    }


}