<?php
/**
 * Unit Test for the Admin Panel Controller
 *
 * This file contains the unit test for the Admin Panel Controller
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace RcmAdminTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\Site;
use RcmAdmin\Controller\AdminPanelController;
use RcmUser\User\Result;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\View\Model\ViewModel;


/**
 * Unit Test for the Admin Panel Controller
 *
 * Unit Test for the Admin Panel Controller
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class AdminPanelControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \RcmAdmin\Controller\AdminPanelController */
    protected $controller;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUserService;

    protected $mockAclService;

    /** @var  \Rcm\Entity\Site */
    protected $currentSite;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockCmsPermissionCheck;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->mockUserService = $this
            ->getMockBuilder('RcmUser\Service\RcmUserService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->currentSite = new Site();
        $this->currentSite->setSiteId(1);

        $this->mockCmsPermissionCheck = $this->getMockBuilder('\Rcm\Acl\CmsPermissionChecks')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockAclService = $this->getMockBuilder('\RcmUser\Acl\Service\AclDataService')
            ->disableOriginalConstructor()
            ->getMock();

        $result = new Result();

        $this->mockAclService->expects($this->any())
            ->method('getRulesByResource')
            ->will($this->returnValue($result));

        $this->mockAclService->expects($this->any())
            ->method('getAllRoles')
            ->will($this->returnValue($result));

        $userService = $this->mockUserService;

        $config = $this->getConfig();

        /** @var \RcmUser\Service\RcmUserService $userService */
        $this->controller = new AdminPanelController(
            $config,
            $this->currentSite,
            $this->mockCmsPermissionCheck
        );

        $event = new MvcEvent();
        $routeMatch = new RouteMatch(['page' => 'index', 'pageType' => 'n']);
        $event->setRouteMatch($routeMatch);

        $this->controller->setEvent($event);
    }

    /**
     * Get admin panel config for tests
     *
     * @return array
     */
    protected function getConfig()
    {
        return [
            'Page' => [
                'display' => 'Page',
                'aclGroups' => 'admin',
                'cssClass' => '',
                'href' => '#',
                'links' => [
                    'New' => [
                        'display' => 'New',
                        'aclGroups' => 'admin',
                        'cssClass' => 'newPageIcon',
                        'href' => '#',
                        'links' => [
                            'Page' => [
                                'display' => 'Page',
                                'aclResource' => 'admin',
                                'aclPermissions' => 'page.new',
                                'cssClass' => 'rcmNewPageIcon rcmNewPage',
                                'href' => '#',
                                'data-title' => 'New Page',
                            ],
                        ]
                    ],
                    'Edit' => [
                        'display' => 'Edit',
                        'aclGroups' => 'admin',
                        'cssClass' => 'draftsIcon',
                        'href' => '#',
                        'links' => [
                            'Page' => [
                                'display' => 'Edit Content',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmEditPageIcon rcmEditPage',
                                'href' => '#',
                            ],
                            'Page Layout' => [
                                'display' => 'Add/Remove Plugins on Page',
                                'aclGroups' => 'admin',
                                'cssClass' => 'rcmLayoutIcon rcmShowLayoutEditor',
                                'href' => '#',
                            ],
                            'Page Properties' => [
                                'display' => 'Page Properties',
                                'aclGroups' => 'admin',
                                'cssClass' => 'PagePropertiesIcon rcmPageProperties',
                                'href' => '#',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Test the constructor is working
     *
     * @return void
     * @covers RcmAdmin\Controller\AdminPanelController::__construct
     */
    public function testConstructor()
    {
        $this->assertTrue($this->controller instanceof AdminPanelController);
    }


    /**
     * Test getAdminWrapperAction
     *
     * @return void
     * @covers RcmAdmin\Controller\AdminPanelController::getAdminWrapperAction
     */
    public function testGetAdminWrapperAction()
    {
        $this->mockCmsPermissionCheck->expects($this->once())
            ->method('siteAdminCheck')
            ->with(
                $this->equalTo($this->currentSite)
            )->will($this->returnValue(true));

        /** @var ViewModel $result */
        $result = $this->controller->getAdminWrapperAction();

        $this->assertTrue($result instanceof ViewModel);

        $expected = $this->getConfig();

        $actual = $result->getVariable('adminMenu');

        $this->assertEquals($expected, $actual);
    }

//    /**
//     * Test getAdminWrapperAction User not Allowed to see admin panel
//     *
//     * @return void
//     * @covers RcmAdmin\Controller\AdminPanelController::getAdminWrapperAction
//     */
//    public function testGetAdminWrapperActionNotAllowed()
//    {
//        $this->mockUserService->expects($this->once())
//            ->method('isAllowed')
//            ->with(
//                $this->equalTo('sites.1'),
//                $this->equalTo('admin'),
//                $this->equalTo('Rcm\Acl\ResourceProvider')
//            )->will($this->returnValue(false));
//
//        $result = $this->controller->getAdminWrapperAction();
//
//        $this->assertNull($result);
//    }
}