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
use Rcm\Entity\Site;
use Rcm\Http\Response;
use Rcm\Renderer\PageRenderer;
use Rcm\Service\PageTypes;

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
     * Setup for tests
     *
     * @return null
     */
    public function setUp()
    {
        $this->pageRenderer = $this
            ->getMockBuilder(PageRenderer::class)
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
