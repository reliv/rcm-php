<?php
/**
 * Unit Test for the Layout Manager Service
 *
 * This file contains the unit test for the Layout Manager Service
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

namespace RcmTest\Service;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Service\LayoutManager;

/**
 * Unit Test for the Layout Manager Service
 *
 * Unit Test for the Layout Manager Service
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class LayoutManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Rcm\Service\LayoutManager */
    protected $layoutManager;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockSiteManager;

    /**
     * Setup for tests
     *
     * @return void
     */
    public function setup()
    {
        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockSiteManager = $mockSiteManager;
    }

    /**
     * Get a layout manager for testing
     *
     * @param array $config Config to use for testing
     *
     * @return LayoutManager
     */
    public function getLayoutManager($config = array())
    {
        $mockSiteManager = $this->mockSiteManager;

        /** @var \Rcm\Service\SiteManager $mockSiteManager */
        return new LayoutManager(
            $mockSiteManager,
            $config
        );

    }

    /**
     * Get the needed config for testing
     *
     * @param bool $skipGenericTheme  Skip adding the Generic theme
     * @param bool $skipLayoutMain    Skip All Layouts but default
     * @param bool $skipLayoutDefault Skip the Default Layout
     * @param bool $skipPageMain      Skip All Page Layouts but default
     * @param bool $skipPageDefault   Skip Default Page Layout
     *
     * @return array
     */
    protected function getBaseConfig(
        $skipGenericTheme = false,
        $skipLayoutMain = false,
        $skipLayoutDefault = false,
        $skipPageMain = false,
        $skipPageDefault = false
    ) {
        $genericTheme = array();
        $layoutDefault = array();
        $layoutMain = array();
        $pageDefault = array();
        $pageMain = array();


        if (!$skipLayoutMain) {
            $layoutMain = array(
                'TestHomePage' => array(
                    'display' => 'Home Page',
                    'file' => 'test-home-page.phtml',
                    'screenShot' => 'home-page.png',
                ),
                'TestOtherTemplatePage' => array(
                    'display' => 'One Column',
                    'file' => 'test-other-page.phtml',
                    'screenShot' => 'test-other-page.png',
                ),
            );
        }

        if (!$skipLayoutDefault) {
            $layoutDefault = array(
                'default' => array(
                    'display' => 'Interior Page',
                    'file' => 'test-interior-page.phtml',
                    'screenShot' => 'interior-page.png',
                    'hidden' => true,
                ),
            );
        }

        if (!$skipPageMain) {
            $pageMain = array(
                'SomeOtherPageTemplate' => array(
                    'display' => 'Some Other Page Template',
                    'file' => 'other-page.phtml',
                    'screenShot' => 'other-page-screen-shot.png',
                ),
            );
        }

        if (!$skipPageDefault) {
            $pageDefault = array(
                'default' => array(
                    'display' => 'Interior Page',
                    'file' => 'page.phtml',
                    'screenShot' => 'default-screen-shot.png',
                ),
            );
        }

        if (!$skipGenericTheme) {
            $genericTheme = array(
                'generic' => array(
                    'screenShot' => 'GenericTestTheme.png',
                    'display' => 'Generic Test Theme',
                    'layouts' => array_merge($layoutDefault, $layoutMain),
                    'pages' => array_merge($pageDefault, $pageMain),
                ),
            );
        }

        $themes = array(
            'RelivTestTheme' => array(
                'screenShot' => 'RelivTestTheme.png',
                'display' => 'Reliv Test Theme',
                'layouts' => array_merge($layoutDefault, $layoutMain),
                'pages' => array_merge($pageDefault, $pageMain),
            ),
        );

        return array(
            'Rcm' => array(
                'themes' => array_merge($themes, $genericTheme)
            ),
        );
    }

    /**
     * Test the constructor
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            '\Rcm\Service\LayoutManager',
            $this->getLayoutManager()
        );
    }

    /**
     * Test getThemesConfig
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getThemesConfig
     */
    public function testGetThemesConfig()
    {
        $config = $this->getBaseConfig();

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes'];

        $result = $layoutManager->getThemesConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getThemesConfig no config found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getThemesConfig
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetThemesConfigWithNoConfig()
    {
        $config = array();

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getThemesConfig();
    }

    /**
     * Test getThemeConfig
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getThemeConfig
     */
    public function testGetThemeConfig()
    {
        $config = $this->getBaseConfig();

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['RelivTestTheme'];

        $result = $layoutManager->getThemeConfig('RelivTestTheme');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getThemeConfig returns default when theme not found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getThemeConfig
     */
    public function testGetThemeConfigReturnsDefaultWhenThemeNotFound()
    {
        $config = $this->getBaseConfig();

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['generic'];

        $result = $layoutManager->getThemeConfig('NotFound');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getThemeConfig when theme not found and generic unavailable
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getThemeConfig
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetThemeConfigNoThemesFound()
    {
        $config = $this->getBaseConfig(true);

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getThemeConfig('NotFound');
    }

    /**
     * Test getSiteThemeLayoutsConfig
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemeLayoutsConfig
     */
    public function testGetSiteThemeLayoutsConfig()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['RelivTestTheme']['layouts'];

        $result = $layoutManager->getSiteThemeLayoutsConfig(22);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteThemeLayoutsConfig for current Site
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemeLayoutsConfig
     */
    public function testGetSiteThemeLayoutsConfigCurrentSite()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(22));

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['RelivTestTheme']['layouts'];

        $result = $layoutManager->getSiteThemeLayoutsConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteThemeLayoutsConfig No Themes Found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemeLayoutsConfig
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetSiteThemeLayoutsConfigConfigNotFound()
    {
        $config = $this->getBaseConfig(false, true, true);

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSiteThemeLayoutsConfig(22);
    }

    /**
     * Test getSiteThemeLayoutsConfig With Invalid Site Id
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemeLayoutsConfig
     *
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetSiteThemeLayoutsConfigInvalidSiteId()
    {
        $config = array();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->mockSiteManager->expects($this->never())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSiteThemeLayoutsConfig(22);
    }

    /**
     * Test getSiteLayout
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteLayout
     */
    public function testGetLayout()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $themeLayoutConfig
            = $config['Rcm']['themes']['RelivTestTheme']['layouts'];

        $expected = $themeLayoutConfig['TestHomePage']['file'];

        $result = $layoutManager->getSiteLayout('TestHomePage', 22);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteLayout returns default if layout not found in config
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteLayout
     */
    public function testGetLayoutGetsDefault()
    {
        $config = $this->getBaseConfig(false, true);

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $themeLayoutConfig
            = $config['Rcm']['themes']['RelivTestTheme']['layouts'];

        $expected = $themeLayoutConfig['default']['file'];

        $result = $layoutManager->getSiteLayout('TestHomePage', 22);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteLayout when no layout file is found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteLayout
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetSiteLayoutNoLayoutFileFound()
    {
        $config = $this->getBaseConfig(false, true);

        $config['Rcm']['themes']['RelivTestTheme']['layouts']['default']['file']
            = null;

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSiteLayout('TestHomePage', 22);
    }

    /**
     * Test getSiteLayout when no layout param is passed
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteLayout
     */
    public function testGetSiteLayoutNoLayoutParam()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteDefaultLayout')
            ->will($this->returnValue('TestHomePage'));

        $layoutManager = $this->getLayoutManager($config);

        $themeLayoutConfig
            = $config['Rcm']['themes']['RelivTestTheme']['layouts'];

        $expected = $themeLayoutConfig['TestHomePage']['file'];

        $result = $layoutManager->getSiteLayout();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteThemePagesTemplateConfig
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemePagesTemplateConfig
     */
    public function testGetSiteThemePagesTemplateConfig()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['RelivTestTheme']['pages'];

        $result = $layoutManager->getSiteThemePagesTemplateConfig(22);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteThemePagesTemplateConfig Current Site
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemePagesTemplateConfig
     */
    public function testGetSiteThemePagesTemplateConfigForCurrentSite()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(22));

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $expected = $config['Rcm']['themes']['RelivTestTheme']['pages'];

        $result = $layoutManager->getSiteThemePagesTemplateConfig();

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSiteThemePageTemplateConfig Invalid Site Id
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemePagesTemplateConfig
     *
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testGetSiteThemePagesTemplateConfigInvalidSiteId()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(false));

        $this->mockSiteManager->expects($this->never())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSiteThemePagesTemplateConfig(22);
    }

    /**
     * Test getSiteThemePagesTemplateConfig With no page templates defined
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSiteThemePagesTemplateConfig
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetSiteThemePagesTemplateConfigWithNoPageTemplates()
    {
        $config = $this->getBaseConfig(false, false, false, true, true);

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSiteThemePagesTemplateConfig(22);
    }

    /**
     * Test getSitePageTemplateConfig Current Site
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSitePageTemplateConfig
     */
    public function testGetSitePageTemplateConfigForCurrentSite()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(22));

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $themeConfig = $config['Rcm']['themes']['RelivTestTheme'];

        $expected = $themeConfig['pages']['SomeOtherPageTemplate'];

        $result = $layoutManager->getSitePageTemplateConfig(
            'SomeOtherPageTemplate'
        );

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSitePageTemplateConfig Returns default config when not found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSitePageTemplateConfig
     */
    public function testGetSitePageTemplateConfigReturnsDefaultWhenNotFound()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(22));

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $themeConfig = $config['Rcm']['themes']['RelivTestTheme'];

        $expected = $themeConfig['pages']['default'];

        $result = $layoutManager->getSitePageTemplateConfig('not-found');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSitePageTemplateConfig With no page templates defined
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSitePageTemplateConfig
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetSitePageTemplateConfigWithNoPageTemplates()
    {
        $config = $this->getBaseConfig(false, false, false, false, true);

        $themeConfig = & $config['Rcm']['themes']['RelivTestTheme'];
        $themeConfig['pages']['SomeOtherPageTemplate'] = null;

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSitePageTemplateConfig('not-here');
    }

    /**
     * Test getSitePageTemplate Current Site
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSitePageTemplate
     */
    public function testGetSitePageTemplate()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('getCurrentSiteId')
            ->will($this->returnValue(22));

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $themeConfig = $config['Rcm']['themes']['RelivTestTheme'];

        $expected = $themeConfig['pages']['SomeOtherPageTemplate']['file'];

        $result = $layoutManager->getSitePageTemplate('SomeOtherPageTemplate');

        $this->assertEquals($expected, $result);
    }

    /**
     * Test getSitePageTemplate With no page template file defined
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getSitePageTemplate
     *
     * @expectedException \Rcm\Exception\RuntimeException
     */
    public function testGetSitePageTemplateWithNoPageTemplateFile()
    {
        $config = $this->getBaseConfig(false, false, false, false, true);

        $themeConfig = & $config['Rcm']['themes']['RelivTestTheme'];
        $themeConfig['pages']['SomeOtherPageTemplate']['file'] = null;

        $this->mockSiteManager->expects($this->any())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $layoutManager->getSitePageTemplate('SomeOtherPageTemplate');
    }

    /**
     * Test isLayoutValid
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::isLayoutValid
     */
    public function testIsLayoutValid()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $result = $layoutManager->isLayoutValid('TestHomePage');

        $this->assertTrue($result);
    }

    /**
     * Test isLayoutValid returns false if not found
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::isLayoutValid
     */
    public function testIsLayoutValidReturnsFalse()
    {
        $config = $this->getBaseConfig();

        $this->mockSiteManager->expects($this->once())
            ->method('isValidSiteId')
            ->will($this->returnValue(true));

        $this->mockSiteManager->expects($this->once())
            ->method('getSiteTheme')
            ->will($this->returnValue('RelivTestTheme'));

        $layoutManager = $this->getLayoutManager($config);

        $result = $layoutManager->isLayoutValid('Not-Here');

        $this->assertFalse($result);
    }


    /**
     * Test getMainLayoutValidator
     *
     * @return void
     *
     * @covers \Rcm\Service\LayoutManager::getMainLayoutValidator
     */
    public function testGetMainLayoutValidator()
    {
        $this->assertInstanceOf(
            '\Rcm\Validator\MainLayout',
            $this->getLayoutManager()->getMainLayoutValidator()
        );
    }


}










