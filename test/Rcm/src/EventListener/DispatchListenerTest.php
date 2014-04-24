<?php
/**
 * Unit Test for the Dispatch Listener Event
 *
 * This file contains the unit test for Dispatch Listener Event
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

use Rcm\EventListener\DispatchListener;
use RcmTest\Base\BaseTestCase;
use Zend\Mvc\MvcEvent;
use Zend\View\HelperPluginManager;

/**
 * Unit Test for Dispatch Listener Event
 *
 * Unit Test for Dispatch Listener Event
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DispatchListenerTest extends BaseTestCase
{

    /** @var string used for mock */
    protected $title;

    /** @var \stdClass Used for mock */
    protected $mockHeadTitle;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->addModule('Rcm');
        parent::setUp();
    }

    /**
     * Test Set Site Layout
     *
     * @return void
     *
     * @covers \Rcm\EventListener\DispatchListener
     */
    public function testSetSiteLayout()
    {
        $favicon = 'someFavicon';
        $title = 'My Site Title';
        $layout = 'myLayout';

        $mockLayoutManager = $this->getMockBuilder('Rcm\Service\LayoutManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager->expects($this->any())
            ->method('getLayout')
            ->will($this->returnValue($layout));

        $mockSiteManager = $this->getMockBuilder('Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getCurrentSiteInfo')
            ->will(
                $this->returnValue(
                    array(
                        'favIcon' => $favicon,
                        'siteTitle' => $title
                    )
                )
            );

        $mockHeadLink = $this->getMockBuilder('\Zend\View\Helper\HeadLink')
            ->disableOriginalConstructor()
            ->getMock();


        $expectedFavicon = array(
            'rel' => 'shortcut icon',
            'type' => 'image/vnd.microsoft.icon',
            'href' => $favicon,
        );

        $mockHeadLink->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($expectedFavicon))
            ->will($this->returnValue(null));

        $mockBasePath = $this->getMockBuilder('\Zend\View\Helper\BasePath')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHeadTitle = $this->getMockBuilder('\Zend\View\Helper\HeadTitle')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockHeadTitle->expects($this->any())
            ->method('__invoke')
            ->will($this->returnCallback(array($this, 'callBackForHeadTitle')));

        $this->mockHeadTitle->expects($this->any())
            ->method('setSeparator');



        $mockPluginHelper = new HelperPluginManager();
        $mockPluginHelper->setService('headLink', $mockHeadLink);
        $mockPluginHelper->setService('basePath', $mockBasePath);
        $mockPluginHelper->setService('headTitle', $this->mockHeadTitle);


        /** @var \Rcm\Service\LayoutManager $mockLayoutManager */
        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        $listener = new DispatchListener(
            $mockLayoutManager,
            $mockSiteManager,
            $mockPluginHelper
        );

        $event = new MvcEvent();

        $listener->setSiteLayout($event);

        $view = $event->getViewModel();

        $template = $view->getTemplate();

        $this->assertEquals('layout/' . $layout, $template);

        $this->assertEquals($title, $this->title);
    }

    /**
     * Used to mock out head title correctly
     *
     * @param string $title Set Title
     *
     * @return null|string
     */
    public function callBackForHeadTitle($title=null)
    {
        if (!empty($title)) {
            $this->title = $title;

            return $this->mockHeadTitle;
        }

        return $this->mockHeadTitle;
    }
}