<?php
/**
 * Unit Test for the RedirectToPage Controller Plugin
 *
 * This file contains the unit test for the RedirectToPage Controller Plugin
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

use Rcm\Controller\Plugin\RedirectToPage;
use Zend\Http\Response;

/**
 * Unit Test for the RedirectToPage Controller Plugin
 *
 * Unit Test for the RedirectToPage Controller Plugin
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class RedirectToPageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test The Invoke Method
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\RedirectToPage
     */
    public function testInvoke()
    {
        $pageName = 'my-test';
        $pageType = 'z';

        $mockPlugin = $this->getMockBuilder(
            '\Rcm\Controller\Plugin\RedirectToPage'
        )
            ->disableOriginalConstructor()
            ->setMethods(['redirect'])
            ->getMock();

        $mockPlugin->expects($this->once())
            ->method('redirect')
            ->with($this->equalTo($pageName), $this->equalTo($pageType));

        /** @var \Rcm\Controller\Plugin\RedirectToPage $mockPlugin */
        $mockPlugin($pageName, $pageType);
    }

    /**
     * Test redirect to Index Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\RedirectToPage
     */
    public function testRedirectToIndexPage()
    {
        $pageName = 'index';
        $pageType = 'n';

        $mockRedirect = $this->getMockBuilder(
            '\Zend\Mvc\Controller\Plugin\Redirect'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirect->expects($this->once())
            ->method('toUrl')
            ->with($this->equalTo('/'));

        $controller = $this->getMockBuilder('\Rcm\Controller\IndexController')
            ->disableOriginalConstructor()
            ->getMock();

        $controller->expects($this->once())
            ->method('__call')
            ->will($this->returnValue($mockRedirect));

        $plugin = new RedirectToPage();
        $plugin->setController($controller);
        $plugin->redirect($pageName, $pageType);
    }

    /**
     * Test redirect to Normal type Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\RedirectToPage
     */
    public function testRedirectToNormalTypePage()
    {
        $pageName = 'my-test';
        $pageType = 'n';

        $mockRedirect = $this->getMockBuilder(
            '\Zend\Mvc\Controller\Plugin\Redirect'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirect->expects($this->once())
            ->method('toRoute')
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

        $plugin = new RedirectToPage();
        $plugin->setController($controller);
        $plugin->redirect($pageName, $pageType);
    }

    /**
     * Test redirect to non basic type Page
     *
     * @return void
     *
     * @covers \Rcm\Controller\Plugin\RedirectToPage
     */
    public function testRedirectToCustomTypePage()
    {
        $pageName = 'my-test';
        $pageType = 'z';

        $mockRedirect = $this->getMockBuilder(
            '\Zend\Mvc\Controller\Plugin\Redirect'
        )
            ->disableOriginalConstructor()
            ->getMock();

        $mockRedirect->expects($this->once())
            ->method('toRoute')
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

        $plugin = new RedirectToPage();
        $plugin->setController($controller);
        $plugin->redirect($pageName, $pageType);
    }
}