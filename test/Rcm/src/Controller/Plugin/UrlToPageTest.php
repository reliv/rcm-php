<?php
/**
 * Unit Test for the UrlToPage Controller Plugin
 *
 * This file contains the unit test for the UrlToPage Controller Plugin
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
namespace RcmTest\Controller\Plugin;

require_once __DIR__ . '/../../../../autoload.php';

use Rcm\Controller\Plugin\UrlToPage;
use Zend\Http\Response;

/**
 * Unit Test for the UrlToPage Controller Plugin
 *
 * Unit Test for the UrlToPage Controller Plugin
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class UrlToPageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test The Invoke Method
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\UrlToPage
     */
    public function testInvoke()
    {
        $pageName = 'my-test';
        $pageType = 'z';

        $mockPlugin = $this->getMockBuilder('\Rcm\Controller\Plugin\UrlToPage')
            ->disableOriginalConstructor()
            ->setMethods(['url'])
            ->getMock();

        $mockPlugin->expects($this->once())
            ->method('url')
            ->with($this->equalTo($pageName), $this->equalTo($pageType));

        /** @var \Rcm\Controller\Plugin\RedirectToPage $mockPlugin */
        $mockPlugin($pageName, $pageType);
    }

    /**
     * Test url to Index Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\UrlToPage
     */
    public function testUrlToIndexPage()
    {
        $pageName = 'index';
        $pageType = 'n';

        $controller = $this->getMockBuilder('\Rcm\Controller\IndexController')
            ->disableOriginalConstructor()
            ->getMock();

        $plugin = new UrlToPage();
        $plugin->setController($controller);
        $result = $plugin->url($pageName, $pageType);

        $this->assertEquals('/', $result);
    }

    /**
     * Test Url to Normal type Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\UrlToPage
     */
    public function testUrlToNormalTypePage()
    {
        $pageName = 'my-test';
        $pageType = 'n';

        $mockRedirect = $this->getMockBuilder('\Zend\Mvc\Controller\Plugin\Url')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirect->expects($this->once())
            ->method('fromRoute')
            ->with(
                $this->equalTo('contentManager'),
                $this->equalTo(['page' => $pageName])
            );

        $controller = $this->getMockBuilder('\Rcm\Controller\IndexController')
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($mockRedirect));

        $plugin = new UrlToPage();
        $plugin->setController($controller);
        $plugin->url($pageName, $pageType);
    }

    /**
     * Test url to non basic type Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\UrlToPage
     */
    public function testUrlToCustomTypePage()
    {
        $pageName = 'my-test';
        $pageType = 'z';

        $mockRedirect = $this->getMockBuilder('\Zend\Mvc\Controller\Plugin\Url')
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirect->expects($this->once())
            ->method('fromRoute')
            ->with(
                $this->equalTo('contentManagerWithPageType'),
                $this->equalTo(
                    [
                        'pageType' => $pageType,
                        'page' => $pageName,
                    ]
                )
            );

        $controller = $this->getMockBuilder('\Rcm\Controller\IndexController')
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($mockRedirect));

        $plugin = new UrlToPage();
        $plugin->setController($controller);
        $plugin->url($pageName, $pageType);
    }
}