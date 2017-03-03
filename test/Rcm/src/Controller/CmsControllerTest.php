<?php
/**
 * Unit Test for the CmsController
 *
 * This file contains the unit test for the CmsController
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

use Rcm\Controller\CmsController;
use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Page\Renderer\PageRendererBc;
use Rcm\Renderer\PageRenderer;
use Zend\Http\Response;

/**
 * Unit Test for the CmsController
 *
 * Unit Test for the CmsController
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class CmsControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageRendererBc
     */
    protected $pageRenderer;

    /**
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        /** @var PageRendererBc pageRenderer */
        $this->pageRenderer = $this
            ->getMockBuilder(PageRendererBc::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pageRenderer->expects($this->any())
            ->method('renderZf2ByName')
            ->will($this->returnValue($this->response));

        $this->site = $this
            ->getMockBuilder(Site::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->page = $this
            ->getMockBuilder(Page::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCmsResponse()
    {
        $unit = new CmsController(
            $this->pageRenderer,
            $this->site
        );

        $result = $unit->getCmsResponse(
            $this->site,
            $this->page
        );

        $this->assertEquals(
            $result,
            $this->response
        );
    }
}
