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
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;

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

        $this->page->setSiteLayoutOverride('default');

        $actual = $this->page->getSiteLayoutOverride();

        $this->assertEquals(null, $actual);
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

    /**
     * Test Clone
     *
     * @return void
     *
     * @covers \Rcm\Entity\Page
     */
    public function testClone()
    {
        $site = new Site();
        $site->setSiteId(55);

        $container = [
            'pageId' => '200',
            'name' => 'pageOne',
            'author' => 'Westin Shafer',
            'createdDate' => new \DateTime('yesterday'),
            'lastPublished' => new \DateTime('yesterday'),
            'revisions' => [
                0 => [
                    'revisionId' => 100,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'publishedDate' => new \DateTime('yesterday'),
                    'published' => true,
                    'md5' => 'revisionMD5',
                    'instances' => [
                        0 => [
                            'pluginWrapperId' => 43,
                            'layoutContainer' => 'layoutOne',
                            'renderOrder' => 0,
                            'height' => 32,
                            'width' => 100,
                            'divFloat' => 'right',
                            'instance' => [
                                'pluginInstanceId' => 44,
                                'plugin' => 'MockPlugin',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => [
                                    'var1' => 1,
                                    'var2' => 2
                                ],
                                'md5' => 'firstMd5'
                            ],
                        ],

                        1 => [
                            'pluginWrapperId' => 45,
                            'layoutContainer' => 'layoutTwo',
                            'renderOrder' => 1,
                            'height' => 33,
                            'width' => 101,
                            'divFloat' => 'none',
                            'instance' => [
                                'pluginInstanceId' => 46,
                                'plugin' => 'MockPlugin2',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide',
                                'instanceConfig' => [
                                    'var3' => 3,
                                    'var4' => 4
                                ],
                                'md5' => 'secondMd5'
                            ],
                        ],
                    ],
                ],

                1 => [
                    'revisionId' => 101,
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('-1 month'),
                    'publishedDate' => new \DateTime('-1 month'),
                    'published' => false,
                    'md5' => 'revision2MD5',
                    'instances' => [
                        0 => [
                            'pluginWrapperId' => 47,
                            'layoutContainer' => 'layoutThree',
                            'renderOrder' => 2,
                            'height' => 33,
                            'width' => 102,
                            'divFloat' => 'right',
                            'instance' => [
                                'pluginInstanceId' => 48,
                                'plugin' => 'MockPlugin3',
                                'siteWide' => false,
                                'displayName' => null,
                                'instanceConfig' => [
                                    'var1' => 1,
                                    'var2' => 2
                                ],
                                'md5' => 'firstMd5'
                            ],
                        ],

                        1 => [
                            'pluginWrapperId' => 49,
                            'layoutContainer' => 'layoutFour',
                            'renderOrder' => 3,
                            'height' => 34,
                            'width' => 103,
                            'divFloat' => 'left',
                            'instance' => [
                                'pluginInstanceId' => 50,
                                'plugin' => 'MockPlugin4',
                                'siteWide' => true,
                                'displayName' => 'TestSiteWide2',
                                'instanceConfig' => [
                                    'var3' => 3,
                                    'var4' => 4
                                ],
                                'md5' => 'secondMd5'
                            ],
                        ],
                    ]
                ]
            ],
        ];

        $this->page->setPageId($container['pageId']);
        $this->page->setName($container['name']);
        $this->page->setAuthor($container['author']);
        $this->page->setCreatedDate($container['createdDate']);
        $this->page->setLastPublished($container['lastPublished']);
        $this->page->setSite($site);

        foreach ($container['revisions'] as $index => $revisionData) {
            $revision = new Revision();
            $revision->setRevisionId($revisionData['revisionId']);
            $revision->setAuthor($revisionData['author']);
            $revision->setCreatedDate($revisionData['createdDate']);
            $revision->publishRevision();
            $revision->setPublishedDate($revisionData['publishedDate']);
            $revision->setMd5($revisionData['md5']);

            foreach ($revisionData['instances'] as $instance) {
                $plugin = new PluginInstance();
                $plugin->setInstanceId($instance['instance']['pluginInstanceId']);
                $plugin->setPlugin($instance['instance']['plugin']);

                if ($instance['instance']['siteWide']) {
                    $plugin->setSiteWide();
                }

                $plugin->setDisplayName($instance['instance']['displayName']);
                $plugin->setInstanceConfig($instance['instance']['instanceConfig']);
                $plugin->setMd5($instance['instance']['md5']);

                $wrapper = new PluginWrapper();
                $wrapper->setPluginWrapperId($instance['pluginWrapperId']);
                $wrapper->setLayoutContainer($instance['layoutContainer']);
                $wrapper->setRenderOrderNumber($instance['renderOrder']);
                $wrapper->setHeight($instance['height']);
                $wrapper->setWidth($instance['width']);
                $wrapper->setDivFloat($instance['divFloat']);
                $wrapper->setInstance($plugin);

                $revision->addPluginWrapper($wrapper);
            }

            if ($index === 0) {
                $this->page->setPublishedRevision($revision);
            } elseif ($index === 1) {
                $this->page->setStagedRevision($revision);
            }

            $this->page->addRevision($revision);
        }

        $this->assertCount(2, $this->page->getRevisions());

        $clonedContainer = clone $this->page;

        /* Test Container */
        $this->assertNotEquals(
            $this->page->getPageId(),
            $clonedContainer->getPageId()
        );

        $this->assertNull($clonedContainer->getPageId());
        $this->assertNull($clonedContainer->getStagedRevision());
        $this->assertCount(1, $clonedContainer->getRevisions());

        $this->assertNull($clonedContainer->getName());
        $this->assertNull($clonedContainer->getParent());

        $currentRevision = $this->page->getPublishedRevision();
        $clonedCurrentRev = $clonedContainer->getPublishedRevision();

        /* Test Revision */
        $this->assertNotEquals(
            $currentRevision->getRevisionId(),
            $clonedCurrentRev->getRevisionId()
        );

        $this->assertNull($clonedCurrentRev->getRevisionId());

        $this->assertEquals(
            $currentRevision->getAuthor(),
            $clonedCurrentRev->getAuthor()
        );

        $this->assertNotEquals(
            $currentRevision->getCreatedDate(),
            $clonedCurrentRev->getCreatedDate()
        );

        $this->assertTrue($clonedCurrentRev->wasPublished());

        $this->assertEquals(
            $currentRevision->getMd5(),
            $clonedCurrentRev->getMd5()
        );

        $revisionWrappers = $currentRevision->getPluginWrappers();
        $clonedWrappers = $clonedCurrentRev->getPluginWrappers();


        $this->assertNotEquals($revisionWrappers, $clonedWrappers);

        /** @var \Rcm\Entity\PluginWrapper $clonedWrapper */
        foreach ($clonedWrappers as $clonedWrapper) {
            if (!$clonedWrapper->getInstance()->isSiteWide()) {
                $this->assertNull($clonedWrapper->getInstance()->getInstanceId());
            } else {
                $this->assertNotNull($clonedWrapper->getInstance()->getInstanceId());
            }
        }

        $page = new Page();

        $clone = clone($page);

        $this->assertInstanceOf('\Rcm\Entity\Page', $clone);
    }

    public function testUtilities()
    {
        $data = [];
        $data['name'] = 'TESTNAME';
        $data['pageTitle'] = 'TESTTITLE';
        $data['pageType'] = 'n';
        $data['description'] = 'TESTDESC';
        $data['keywords'] = 'KEY,WORD';
        $data['author'] = 'TESTAUTHOR';
        $data['pageLayout'] = 'TESTPAGELAYOUT';
        $data['siteLayoutOverride'] = 'TESTLAYOUTOVERRIDE';
        $data['parent'] = null;


        $obj1 = new Page();

        $obj1->populate($data);

        $this->assertEquals($data['name'], $obj1->getName());
        $this->assertEquals($data['pageTitle'], $obj1->getPageTitle());
        $this->assertEquals($data['pageType'], $obj1->getPageType());
        $this->assertEquals($data['description'], $obj1->getDescription());
        $this->assertEquals($data['keywords'], $obj1->getKeywords());
        $this->assertEquals($data['author'], $obj1->getAuthor());
        $this->assertEquals($data['pageLayout'], $obj1->getPageLayout());
        $this->assertEquals($data['siteLayoutOverride'], $obj1->getSiteLayoutOverride());
        $this->assertEquals($data['parent'], $obj1->getParent());

        $data['parent'] = new Page();

        $obj1->populate($data);

        $this->assertEquals($data['parent'], $obj1->getParent());

        //

        $json = json_encode($obj1);

        $this->assertJson($json);

        $iterator = $obj1->getIterator();

        $this->assertInstanceOf('\ArrayIterator', $iterator);

        $array = $obj1->toArray();

        $this->assertEquals($data['name'], $array['name']);
        $this->assertEquals($data['pageTitle'], $array['pageTitle']);
        $this->assertEquals($data['pageType'], $array['pageType']);
        $this->assertEquals($data['description'], $array['description']);
        $this->assertEquals($data['keywords'], $array['keywords']);
        $this->assertEquals($data['author'], $array['author']);
        $this->assertEquals($data['pageLayout'], $array['pageLayout']);
        $this->assertEquals($data['siteLayoutOverride'], $array['siteLayoutOverride']);
        $this->assertEquals($data['parent'], $array['parent']);
    }
}
 