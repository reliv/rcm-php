<?php
/**
 * Unit Test for the Site Entity
 *
 * This file contains the unit test for the Site Entity
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

use Doctrine\Common\Collections\ArrayCollection;
use Rcm\Entity\Container;
use Rcm\Entity\Country;
use Rcm\Entity\Domain;
use Rcm\Entity\Language;
use Rcm\Entity\Page;
use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;
use Rcm\Entity\Revision;
use Rcm\Entity\Site;

/**
 * Unit Test for the Site Entity
 *
 * Unit Test for the Site Entity
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class SIteTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Entity\Site */
    protected $site;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->site = new Site();
    }

    /**
     * Test Get and Set Site Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetRevisionId()
    {
        $id = 101;

        $this->site->setSiteId($id);

        $actual = $this->site->getSiteId();

        $this->assertEquals($id, $actual);
    }

    /**
     * Test Get and Set the Domain Object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetDomain()
    {
        $domain = new Domain();
        $domain->setDomainId('102');

        $this->site->setDomain($domain);

        $actual = $this->site->getDomain();

        $this->assertTrue($actual instanceof Domain);
        $this->assertEquals($domain, $actual);
    }

    /**
     * Test Set Domain Only Accepts a Domain object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetDomainOnlyAcceptsDomainObject()
    {
        $this->site->setDomain(time());
    }

    /**
     * Test Get and Set the Language Object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetLanguage()
    {
        $language = new Language();
        $language->setLanguageId('102');

        $this->site->setLanguage($language);

        $actual = $this->site->getLanguage();

        $this->assertTrue($actual instanceof Language);
        $this->assertEquals($language, $actual);
    }

    /**
     * Test Set Language Only Accepts a Language object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetLanguageOnlyAcceptsLanguageObject()
    {
        $this->site->setLanguage(time());
    }

    /**
     * Test Get and Set the Country Object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetCountry()
    {
        $country = new Country();
        $country->setIso3('USA');

        $this->site->setCountry($country);

        $actual = $this->site->getCountry();

        $this->assertTrue($actual instanceof Country);
        $this->assertEquals($country, $actual);
    }

    /**
     * Test Set Country Only Accepts a Country object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testSetCountryOnlyAcceptsCountryObject()
    {
        $this->site->setCountry(time());
    }

    /**
     * Test Get and Set the site Theme
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetTheme()
    {
        $theme = 'my-theme';

        $this->site->setTheme($theme);

        $actual = $this->site->getTheme();

        $this->assertEquals($theme, $actual);
    }

    /**
     * Test Get and Set the site Status
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetStatus()
    {
        $status = 'A';

        $this->site->setStatus($status);

        $actual = $this->site->getStatus();

        $this->assertEquals($status, $actual);
    }

    /**
     * Test Get and Add Pages
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndAddPages()
    {
        $pageOne = new Page();
        $pageOne->setPageId(12);

        $pageTwo = new Page();
        $pageTwo->setPageId(13);

        $pageThree = new Page();
        $pageThree->setPageId(14);

        $expected = array(
            $pageOne,
            $pageTwo,
            $pageThree
        );

        $this->site->addPage($pageOne);
        $this->site->addPage($pageTwo);
        $this->site->addPage($pageThree);

        $actual = $this->site->getPages();

        $this->assertTrue($actual instanceof ArrayCollection);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * Test Remove Page
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testRemovePage()
    {
        $pageOne = new Page();
        $pageOne->setPageId(12);

        $pageTwo = new Page();
        $pageTwo->setPageId(13);

        $pageThree = new Page();
        $pageThree->setPageId(14);

        $expected = array(
            $pageTwo,
            $pageThree
        );

        $this->site->addPage($pageOne);
        $this->site->addPage($pageTwo);
        $this->site->addPage($pageThree);

        $this->site->removePage($pageOne);

        $actual = $this->site->getPages();

        $this->assertTrue($actual instanceof ArrayCollection);

        $reIndexedArray = array_values($actual->toArray());

        $this->assertEquals($expected, $reIndexedArray);
    }

    /**
     * Test Set Page Only Accepts a Page object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testAddPageOnlyAcceptsPageObject()
    {
        $this->site->addPage(time());
    }

    /**
     * Test Remove Page Only Accepts a Page object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testRemovePageOnlyAcceptsPageObject()
    {
        $this->site->removePage(time());
    }

    /**
     * Test Get and Add Containers
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndAddContainers()
    {
        $containerOne = new Container();
        $containerOne->setContainerId(49);

        $containerTwo = new Container();
        $containerTwo->setContainerId(50);

        $containerThree = new Container();
        $containerThree->setContainerId(51);

        $expected = array(
            $containerOne,
            $containerTwo,
            $containerThree
        );

        $this->site->addContainer($containerOne);
        $this->site->addContainer($containerTwo);
        $this->site->addContainer($containerThree);

        $actual = $this->site->getContainers();

        $this->assertTrue($actual instanceof ArrayCollection);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * Test Remove Container
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testRemoveContainer()
    {
        $containerOne = new Container();
        $containerOne->setContainerId(49);

        $containerTwo = new Container();
        $containerTwo->setContainerId(50);

        $containerThree = new Container();
        $containerThree->setContainerId(51);

        $expected = array(
            $containerTwo,
            $containerThree
        );

        $this->site->addContainer($containerOne);
        $this->site->addContainer($containerTwo);
        $this->site->addContainer($containerThree);

        $this->site->removeContainer($containerOne);

        $actual = $this->site->getContainers();

        $this->assertTrue($actual instanceof ArrayCollection);

        $reIndexedArray = array_values($actual->toArray());

        $this->assertEquals($expected, $reIndexedArray);
    }

    /**
     * Test Set Container Only Accepts a Container object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testAddContainerOnlyAcceptsContainerObject()
    {
        $this->site->addContainer(time());
    }

    /**
     * Test Remove Container Only Accepts a Container object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testRemoveContainerOnlyAcceptsContainerObject()
    {
        $this->site->removeContainer(time());
    }

    /**
     * Test Get and Add Site Wide Plugins
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndAddSiteWidePlugins()
    {
        $pluginOne = new PluginInstance();
        $pluginOne->setInstanceId(3);
        $pluginOne->setSiteWide();
        $pluginOne->setDisplayName('Plugin One');

        $pluginTwo = new PluginInstance();
        $pluginTwo->setInstanceId(33);
        $pluginTwo->setSiteWide();
        $pluginTwo->setDisplayName('Plugin Two');

        $pluginThree = new PluginInstance();
        $pluginThree->setInstanceId(33);
        $pluginThree->setSiteWide();
        $pluginThree->setDisplayName('Plugin Three');

        $expected = array(
            $pluginOne,
            $pluginTwo,
            $pluginThree
        );

        $this->site->addSiteWidePlugin($pluginOne);
        $this->site->addSiteWidePlugin($pluginTwo);
        $this->site->addSiteWidePlugin($pluginThree);

        $actual = $this->site->getSiteWidePlugins();

        $this->assertTrue($actual instanceof ArrayCollection);
        $this->assertEquals($expected, $actual->toArray());
    }

    /**
     * Test adding non site wide instances throws exception
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testAddSiteWideWithNonSiteWideMarkedInstanceThrowsException(
    )
    {
        $pluginOne = new PluginInstance();
        $pluginOne->setInstanceId(3);
        $pluginOne->setDisplayName('Plugin One');

        $this->site->addSiteWidePlugin($pluginOne);
    }

    /**
     * Test adding site wide instances with no name throws exception
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testAddSiteWideWithNoNameThrowsException()
    {
        $pluginOne = new PluginInstance();
        $pluginOne->setInstanceId(3);
        $pluginOne->setSiteWide();

        $this->site->addSiteWidePlugin($pluginOne);
    }

    /**
     * Test Remove Container
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testRemoveSiteWidePlugin()
    {
        $pluginOne = new PluginInstance();
        $pluginOne->setInstanceId(3);
        $pluginOne->setSiteWide();
        $pluginOne->setDisplayName('Plugin One');

        $pluginTwo = new PluginInstance();
        $pluginTwo->setInstanceId(33);
        $pluginTwo->setSiteWide();
        $pluginTwo->setDisplayName('Plugin Two');

        $pluginThree = new PluginInstance();
        $pluginThree->setInstanceId(33);
        $pluginThree->setSiteWide();
        $pluginThree->setDisplayName('Plugin Three');

        $expected = array(
            $pluginTwo,
            $pluginThree
        );

        $this->site->addSiteWidePlugin($pluginOne);
        $this->site->addSiteWidePlugin($pluginTwo);
        $this->site->addSiteWidePlugin($pluginThree);

        $this->site->removeSiteWidePlugin($pluginOne);

        $actual = $this->site->getSiteWidePlugins();

        $this->assertTrue($actual instanceof ArrayCollection);

        $reIndexedArray = array_values($actual->toArray());

        $this->assertEquals($expected, $reIndexedArray);
    }

    /**
     * Test Add Site Wide Plugin Only Accepts a Container object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testAddSiteWidePluginOnlyAcceptsPluginInstanceObject()
    {
        $this->site->addSiteWidePlugin(time());
    }

    /**
     * Test Remove Site Wide Plugin Only Accepts a Container object
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testRemoveSiteWidePluginOnlyAcceptsPluginInstanceObject()
    {
        $this->site->removeSiteWidePlugin(time());
    }

    /**
     * Test Get and Set Site Favicon
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetFavIcon()
    {
        $favicon = '/some/path.ico';

        $this->site->setFavIcon($favicon);

        $actual = $this->site->getFavIcon();

        $this->assertEquals($favicon, $actual);
    }

    /**
     * Test Get and Set Site Title
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetTitle()
    {
        $title = 'My Title';

        $this->site->setSiteTitle($title);

        $actual = $this->site->getSiteTitle();

        $this->assertEquals($title, $actual);
    }

    /**
     * Test Get and Set Login Page
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetLoginPage()
    {
        $path = '/my/login';

        $this->site->setLoginPage($path);

        $actual = $this->site->getLoginPage();

        $this->assertEquals($path, $actual);
    }

    /**
     * Test Clone
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testClone()
    {
        $domain = new Domain();
        $domain->setDomainId(23);

        $language = new Language();
        $language->setLanguageId(55);

        $country = new Country();
        $country->setIso3('USA');

        $site = array(
            'siteId' => '1000',
            'owner' => 'wshafer',
            'domain' => $domain,
            'theme' => 'theme1',
            'siteLayout' => 'layoutOne',
            'siteTitle' => 'My Title',
            'language' => $language,
            'status' => 'A',
            'favicon' => 'icon.jpg',
            'loginRequired' => true,
            'loginPage' => 'login.html',
            'aclRoles' => 'role1,role2',
            'pages' => array(
                0 => array(
                    'pageId' => '200',
                    'name' => 'pageOne',
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'lastPublished' => new \DateTime('yesterday'),
                    'revisions' => array(
                        0 => array(
                            'revisionId' => 100,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('yesterday'),
                            'publishedDate' => new \DateTime('yesterday'),
                            'published' => true,
                            'md5' => 'revisionMD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 43,
                                    'layoutContainer' => 'layoutOne',
                                    'renderOrder' => 0,
                                    'height' => 32,
                                    'width' => 100,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 80,
                                        'plugin' => 'MockPlugin',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 45,
                                    'layoutContainer' => 'layoutTwo',
                                    'renderOrder' => 1,
                                    'height' => 33,
                                    'width' => 101,
                                    'divFloat' => 'none',
                                    'instance' => array(
                                        'pluginInstanceId' => 81,
                                        'plugin' => 'MockPlugin2',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            ),
                        ),

                        1 => array(
                            'revisionId' => 101,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('-1 month'),
                            'publishedDate' => new \DateTime('-1 month'),
                            'published' => false,
                            'md5' => 'revision2MD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 47,
                                    'layoutContainer' => 'layoutThree',
                                    'renderOrder' => 2,
                                    'height' => 33,
                                    'width' => 102,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 82,
                                        'plugin' => 'MockPlugin3',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 49,
                                    'layoutContainer' => 'layoutFour',
                                    'renderOrder' => 3,
                                    'height' => 34,
                                    'width' => 103,
                                    'divFloat' => 'left',
                                    'instance' => array(
                                        'pluginInstanceId' => 83,
                                        'plugin' => 'MockPlugin4',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide2',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            )
                        )
                    ),
                ),

                1 => array(
                    'pageId' => '200',
                    'name' => 'pageOne',
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'lastPublished' => new \DateTime('yesterday'),
                    'revisions' => array(
                        0 => array(
                            'revisionId' => 100,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('yesterday'),
                            'publishedDate' => new \DateTime('yesterday'),
                            'published' => true,
                            'md5' => 'revisionMD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 61,
                                    'layoutContainer' => 'layoutOne',
                                    'renderOrder' => 0,
                                    'height' => 32,
                                    'width' => 100,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 84,
                                        'plugin' => 'MockPlugin',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 60,
                                    'layoutContainer' => 'layoutTwo',
                                    'renderOrder' => 1,
                                    'height' => 33,
                                    'width' => 101,
                                    'divFloat' => 'none',
                                    'instance' => array(
                                        'pluginInstanceId' => 85,
                                        'plugin' => 'MockPlugin2',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            ),
                        ),

                        1 => array(
                            'revisionId' => 101,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('-1 month'),
                            'publishedDate' => new \DateTime('-1 month'),
                            'published' => false,
                            'md5' => 'revision2MD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 63,
                                    'layoutContainer' => 'layoutThree',
                                    'renderOrder' => 2,
                                    'height' => 33,
                                    'width' => 102,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 86,
                                        'plugin' => 'MockPlugin3',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 49,
                                    'layoutContainer' => 'layoutFour',
                                    'renderOrder' => 3,
                                    'height' => 34,
                                    'width' => 103,
                                    'divFloat' => 'left',
                                    'instance' => array(
                                        'pluginInstanceId' => 87,
                                        'plugin' => 'MockPlugin4',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide2',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            )
                        )
                    ),
                ),
            ),

            'containers' => array(
                0 => array(
                    'containerId' => '200',
                    'name' => 'containerOne',
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'lastPublished' => new \DateTime('yesterday'),
                    'revisions' => array(
                        0 => array(
                            'revisionId' => 100,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('yesterday'),
                            'publishedDate' => new \DateTime('yesterday'),
                            'published' => true,
                            'md5' => 'revisionMD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 43,
                                    'layoutContainer' => 'layoutOne',
                                    'renderOrder' => 0,
                                    'height' => 32,
                                    'width' => 100,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 998,
                                        'plugin' => 'MockPlugin',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 45,
                                    'layoutContainer' => 'layoutTwo',
                                    'renderOrder' => 1,
                                    'height' => 33,
                                    'width' => 101,
                                    'divFloat' => 'none',
                                    'instance' => array(
                                        'pluginInstanceId' => 88,
                                        'plugin' => 'MockPlugin2',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            ),
                        ),

                        1 => array(
                            'revisionId' => 101,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('-1 month'),
                            'publishedDate' => new \DateTime('-1 month'),
                            'published' => false,
                            'md5' => 'revision2MD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 47,
                                    'layoutContainer' => 'layoutThree',
                                    'renderOrder' => 2,
                                    'height' => 33,
                                    'width' => 102,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 89,
                                        'plugin' => 'MockPlugin3',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 49,
                                    'layoutContainer' => 'layoutFour',
                                    'renderOrder' => 3,
                                    'height' => 34,
                                    'width' => 103,
                                    'divFloat' => 'left',
                                    'instance' => array(
                                        'pluginInstanceId' => 90,
                                        'plugin' => 'MockPlugin4',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide2',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            )
                        )
                    ),
                ),

                1 => array(
                    'containerId' => '200',
                    'name' => 'containerOne',
                    'author' => 'Westin Shafer',
                    'createdDate' => new \DateTime('yesterday'),
                    'lastPublished' => new \DateTime('yesterday'),
                    'revisions' => array(
                        0 => array(
                            'revisionId' => 100,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('yesterday'),
                            'publishedDate' => new \DateTime('yesterday'),
                            'published' => true,
                            'md5' => 'revisionMD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 43,
                                    'layoutContainer' => 'layoutOne',
                                    'renderOrder' => 0,
                                    'height' => 32,
                                    'width' => 100,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 91,
                                        'plugin' => 'MockPlugin',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 45,
                                    'layoutContainer' => 'layoutTwo',
                                    'renderOrder' => 1,
                                    'height' => 33,
                                    'width' => 101,
                                    'divFloat' => 'none',
                                    'instance' => array(
                                        'pluginInstanceId' => 92,
                                        'plugin' => 'MockPlugin2',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            ),
                        ),

                        1 => array(
                            'revisionId' => 101,
                            'author' => 'Westin Shafer',
                            'createdDate' => new \DateTime('-1 month'),
                            'publishedDate' => new \DateTime('-1 month'),
                            'published' => false,
                            'md5' => 'revision2MD5',
                            'instances' => array(
                                0 => array(
                                    'pluginWrapperId' => 47,
                                    'layoutContainer' => 'layoutThree',
                                    'renderOrder' => 2,
                                    'height' => 33,
                                    'width' => 102,
                                    'divFloat' => 'right',
                                    'instance' => array(
                                        'pluginInstanceId' => 93,
                                        'plugin' => 'MockPlugin3',
                                        'siteWide' => false,
                                        'displayName' => null,
                                        'instanceConfig' => array(
                                            'var1' => 1,
                                            'var2' => 2
                                        ),
                                        'md5' => 'firstMd5'
                                    ),
                                ),

                                1 => array(
                                    'pluginWrapperId' => 49,
                                    'layoutContainer' => 'layoutFour',
                                    'renderOrder' => 3,
                                    'height' => 34,
                                    'width' => 103,
                                    'divFloat' => 'left',
                                    'instance' => array(
                                        'pluginInstanceId' => 94,
                                        'plugin' => 'MockPlugin4',
                                        'siteWide' => true,
                                        'displayName' => 'TestSiteWide2',
                                        'instanceConfig' => array(
                                            'var3' => 3,
                                            'var4' => 4
                                        ),
                                        'md5' => 'secondMd5'
                                    ),
                                ),
                            )
                        )
                    ),
                ),
            ),
        );

        $this->site->setSiteId($site['siteId']);
        $this->site->setDomain($site['domain']);
        $this->site->setTheme($site['theme']);
        $this->site->setSiteLayout($site['siteLayout']);
        $this->site->setSiteTitle($site['siteTitle']);
        $this->site->setLanguage($site['language']);
        $this->site->setStatus($site['status']);
        $this->site->setFavIcon($site['favicon']);
        $this->site->setLoginPage($site['loginPage']);

        foreach ($site['pages'] as $page) {
            $pageEntity = new Page();
            $pageEntity->setPageId($page['pageId']);
            $pageEntity->setName($page['name']);
            $pageEntity->setAuthor($page['author']);
            $pageEntity->setCreatedDate($page['createdDate']);
            $pageEntity->setLastPublished($page['lastPublished']);
            $pageEntity->setSite($this->site);

            foreach ($page['revisions'] as $index => $revisionData) {

                $revision = $this->getRevision($revisionData);

                if ($index === 0) {
                    $pageEntity->setPublishedRevision($revision);
                } elseif ($index === 1) {
                    $pageEntity->setStagedRevision($revision);
                }

                $pageEntity->addRevision($revision);
            }

            $this->site->addPage($pageEntity);
        }

        foreach ($site['containers'] as $container) {

            $containerEntity = new Container();
            $containerEntity->setContainerId($container['containerId']);
            $containerEntity->setName($container['name']);
            $containerEntity->setAuthor($container['author']);
            $containerEntity->setCreatedDate($container['createdDate']);
            $containerEntity->setLastPublished($container['lastPublished']);
            $containerEntity->setSite($this->site);

            foreach ($container['revisions'] as $index => $revisionData) {
                $revision = $this->getRevision($revisionData);

                if ($index === 0) {
                    $containerEntity->setPublishedRevision($revision);
                } elseif ($index === 1) {
                    $containerEntity->setStagedRevision($revision);
                }

                $containerEntity->addRevision($revision);
            }

            $this->site->addContainer($containerEntity);
        }


        /** Test site entity is setup correctly */

        $this->assertCount(1, $this->site->getPages());
        $this->assertCount(1, $this->site->getContainers());

        $original = $this->site;
        $cloned = clone $this->site;

        $this->assertNotEquals($original->getSiteId(), $cloned->getSiteId());
        $this->assertNull($cloned->getSiteId());

        $this->assertNull($cloned->getDomain());
        $this->assertEquals($original->getTheme(), $cloned->getTheme());
        $this->assertEquals($original->getSiteLayout(), $cloned->getSiteLayout());
        $this->assertEquals($original->getSiteTitle(), $cloned->getSiteTitle());
        $this->assertEquals($original->getLanguage(), $cloned->getLanguage());
        $this->assertEquals($original->getStatus(), $cloned->getStatus());
        $this->assertEquals($original->getFavIcon(), $cloned->getFavIcon());
        $this->assertEquals($original->getLoginPage(), $cloned->getLoginPage());

        $clonedPages = $cloned->getPages();

        /** @var \Rcm\Entity\Page $page */
        foreach ($clonedPages as $page) {
            $this->assertNull($page->getPageId());

            $clonedRevision = $page->getPublishedRevision();

            if (empty($clonedRevision)) {
                continue;
            }

            $this->assertNull($clonedRevision->getRevisionId());

            $clonedWrappers = $clonedRevision->getPluginWrappers();

            /** @var \Rcm\Entity\PluginWrapper $wrapper */
            foreach($clonedWrappers as $wrapper) {
                $this->assertNull($wrapper->getPluginWrapperId());
                $this->assertNull($wrapper->getInstance()->getInstanceId());
            }
        }

        $clonedContainers = $cloned->getContainers();

        /** @var \Rcm\Entity\Container $container */
        foreach ($clonedContainers as $container) {
            $this->assertNull($container->getContainerId());

            $clonedRevision = $container->getPublishedRevision();

            if (empty($clonedRevision)) {
                continue;
            }

            $this->assertNull($clonedRevision->getRevisionId());

            $clonedWrappers = $clonedRevision->getPluginWrappers();

            /** @var \Rcm\Entity\PluginWrapper $wrapper */
            foreach($clonedWrappers as $wrapper) {
                $this->assertNull($wrapper->getPluginWrapperId());
                $this->assertNull($wrapper->getInstance()->getInstanceId());
            }
        }
    }

    /**
     * Get a container revision
     *
     * @param $revisionData
     *
     * @return Revision
     */
    private function getRevision($revisionData)
    {
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
            $plugin->setDisplayName($instance['instance']['displayName']);
            $plugin->setInstanceConfig($instance['instance']['instanceConfig']);
            $plugin->setMd5($instance['instance']['md5']);

            if ($instance['instance']['siteWide']) {
                $plugin->setSiteWide();
                $this->site->addSiteWidePlugin($plugin);
            }

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

        return $revision;
    }

}
 