<?php
/**
 * Unit Test for the Page Entity
 *
 * This file contains the unit test for the Page Entity
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

namespace RcmTest\Entity;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\Page;

/**
 * Unit Test for Page Entity
 *
 * Unit Test for Page Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Page */
    protected $page;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->page = new Page();
    }

    /**
     * Test Get and Set Page ID
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetPageId()
    {
        $id = 4;

        $this->page->setPageId($id);

        $actual = $this->page->getPageId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Get and Set Page Type
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetPageType()
    {
        $pageType = 'z';

        $this->page->setPageType($pageType);

        $actual = $this->page->getPageType();

        $this->assertEquals($pageType, $actual);
    }

    /**
     * Test Set Page Type Too Long
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetPageTypeTooLong()
    {
        $pageType = 'tooLong';

        $this->page->setPageType($pageType);
    }

    /**
     * Test Get and Set Page Title
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetPageTitle()
    {
        $pageTitle = 'My Page Title';

        $this->page->setPageTitle($pageTitle);

        $actual = $this->page->getPageTitle();

        $this->assertEquals($pageTitle, $actual);
    }

    /**
     * Test Get and Set Description
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetDescription()
    {
        $pageDescription = 'My Page Description';

        $this->page->setDescription($pageDescription);

        $actual = $this->page->getDescription();

        $this->assertEquals($pageDescription, $actual);
    }

    /**
     * Test Get and Set Keywords
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetKeywords()
    {
        $keywords = 'CMS,Content Management,Best WWW Editor Ever';

        $this->page->setKeywords($keywords);

        $actual = $this->page->getKeywords();

        $this->assertEquals($keywords, $actual);
    }

    /**
     * Test Get and Set Page Layout
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetPageLayout()
    {
        $layout = 'mylayout';

        $this->page->setPageLayout($layout);

        $actual = $this->page->getPageLayout();

        $this->assertEquals($layout, $actual);
    }

    /**
     * Test Get and Set Site Layout Override
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetSiteLayoutOverride()
    {
        $layout = 'my-site-override';

        $this->page->setSiteLayoutOverride($layout);

        $actual = $this->page->getSiteLayoutOverride();

        $this->assertEquals($layout, $actual);
    }

    /**
     * Test Get and Set Parent Page
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testGetAndSetParentPage()
    {
        $page = new Page();
        $page->setPageId(55);

        $this->page->setParent($page);

        $actual = $this->page->getParent();

        $this->assertEquals($page, $actual);
    }

    /**
     * Test Set Parent Page Only Accepts a Page object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetSetParentPageOnlyAcceptsPageObject()
    {
        $this->page->setParent(time());
    }
}
 