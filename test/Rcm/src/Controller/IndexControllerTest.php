<?php

namespace RcmTest\Controller;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Controller\IndexController;
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Page\PageTypes\PageTypes;
use Rcm\Page\Renderer\PageRendererBc;

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
    }

    public function testGetCmsResponse()
    {
        $unit = new IndexController(
            $this->pageRenderer,
            $this->site
        );

        $result = $unit->getCmsResponse(
            $this->site,
            'test',
            PageTypes::NORMAL
        );

        $this->assertEquals(
            $result, $this->response
        );
    }
}
