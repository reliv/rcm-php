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
     * Test Get and Set Owner Id
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetOwner()
    {
        $owner = '32155679';

        $this->site->setOwner($owner);

        $actual = $this->site->getOwner();

        $this->assertEquals($owner, $actual);
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
    public function testAddSiteWideWithNonSiteWideMarkedInstanceThrowsException()
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
     * Test Get and Set Login Required
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetLoginRequired()
    {
        $this->site->setLoginRequired(true);

        $this->assertTrue($this->site->isLoginRequired());
    }

    /**
     * Test Get Login Required Default
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetLoginRequiredDefault()
    {
        $this->assertFalse($this->site->isLoginRequired());
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
     * Test Get and Set ACL Roles with Array
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetAclRolesWithArray()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $this->site->addAclRoles($roles);

        $actual = $this->site->getAclRoles();

        $this->assertEquals($roles, $actual);
    }

    /**
     * Test Get and Set ACL Roles with CSV
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetAclRolesWithCsv()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $csv = implode(',', $roles);

        $this->site->addAclRoles($csv);

        $actual = $this->site->getAclRoles();

        $this->assertEquals($roles, $actual);
    }

    /**
     * Test Get and Set ACL Roles with current Roles
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetAclRolesWithCurrentRoles()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $this->site->addAclRoles($roles);

        $roles[] = 'myRoleFour';

        $this->site->addAclRoles('myRoleFour');

        $actual = $this->site->getAclRoles();

        $this->assertEquals($roles, $actual);
    }

    /**
     * Test Get and Set ACL Roles with current Roles and One dup
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testGetAndSetAclRolesWithCurrentRolesAndOneDup()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $this->site->addAclRoles($roles);

        $roles[] = 'myRoleFour';

        $this->site->addAclRoles('myRoleFour, myRoleOne');

        $actual = $this->site->getAclRoles();

        $this->assertEquals($roles, $actual);
    }

    /**
     * Test Has Role
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testHasRole()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $this->site->addAclRoles($roles);

        $this->assertTrue($this->site->hasRole('myRoleOne'));
    }

    /**
     * Test Does Not Have Role
     *
     * @return void
     *
     * @covers \Rcm\Entity\Site
     */
    public function testDoesNotHaveRole()
    {
        $roles = array(
            'myRoleOne',
            'myRoleTwo',
            'myRoleThree'
        );

        $this->site->addAclRoles($roles);

        $this->assertFalse($this->site->hasRole('myRoleFour'));
    }

}
 