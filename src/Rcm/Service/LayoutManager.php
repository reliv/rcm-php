<?php
/**
 * Rcm Layout Manager
 *
 * This file contains the class definition for the Layout Manager
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
namespace Rcm\Service;

use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\RuntimeException;
use Rcm\Validator\MainLayout;

/**
 * Rcm Layout Manager
 *
 * Rcm Layout Manager.  This class handles the RCM theme engine.  Use this to get
 * the correct layouts for pages and sites.  Themes are defined in config as well
 * as in the database.  The Site Entity stores which theme is to be use on the site,
 * while the Page Entity will define a page template to use but can also override
 * the site layout default set from the site entity.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class LayoutManager
{
    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /** @var Array  */
    protected $config;

    /**
     * Constructor
     *
     * @param SiteManager $siteManager Rcm Site Manager
     * @param Array       $config      Config Array
     */
    public function __construct(SiteManager $siteManager, $config)
    {
        $this->siteManager = $siteManager;
        $this->config = $config;
    }

    /**
     * Get the installed RCM Themes configuration.
     *
     * @return array
     * @throws \Rcm\Exception\RuntimeException
     */
    public function getThemesConfig()
    {
        if (empty($this->config['Rcm'])
            || empty($this->config['Rcm']['themes'])
        ) {
            throw new RuntimeException(
                'No Themes found defined in configuration.  Please report the issue
                 with the themes author.'
            );
        }

        return $this->config['Rcm']['themes'];
    }

    /**
     * Get a themes config
     *
     * @param string $theme Theme name to search for
     *
     * @return array
     * @throws \Rcm\Exception\RuntimeException
     */
    public function getThemeConfig($theme)
    {
        $themesConfig = $this->getThemesConfig();

        if (!empty($themesConfig[$theme])) {
            return $themesConfig[$theme];
        } elseif (!empty($themesConfig['generic'])) {
            return $themesConfig['generic'];
        }

        throw new RuntimeException(
            'No theme config found for '.$theme.' and no default theme found'
        );
    }

    /**
     * Find out if selected theme exists and has site layouts defined in config
     * or fallback to generic theme
     *
     * @param integer $siteId Site Id to lookup.  If none passed will get the
     *                        currents theme.
     *
     * @return array Config Array For Theme
     * @throws \Rcm\Exception\RuntimeException
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getSiteThemeLayoutsConfig($siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $theme = $this->siteManager->getSiteTheme($siteId);

        $rcmThemeConfig = $this->getThemeConfig($theme, $siteId);

        if (empty($rcmThemeConfig['layouts'])) {
            throw new RuntimeException(
                'No theme config found for site and no default theme found'
            );
        }

        return $rcmThemeConfig['layouts'];
    }

    /**
     * Get Layout method will attempt to locate and fetch the correct layout
     * for the site and page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.
     *
     * @param string|null  $layout Layout to find
     * @param integer|null $siteId Site to lookup
     *
     * @return string
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function getSiteLayout($layout=null, $siteId=null)
    {
        $themeLayoutConfig = $this->getSiteThemeLayoutsConfig($siteId);

        if (empty($layout)) {
            $layout = $this->siteManager->getSiteDefaultLayout($siteId);
        }

        if (!empty($themeLayoutConfig[$layout])
            && !empty($themeLayoutConfig[$layout]['file'])
        ) {
            return $themeLayoutConfig[$layout]['file'];

        } elseif (!empty($themeLayoutConfig['default'])
            && !empty($themeLayoutConfig['default']['file'])
        ) {
            return $themeLayoutConfig['default']['file'];
        }

        throw new RuntimeException('No Layouts Found in config');
    }

    /**
     * Find out if selected theme exists and has site layouts defined in config
     * or fallback to generic theme
     *
     * @param integer|null $siteId Site Id if none passed in will use current siteId
     *
     * @return array Config Array For Theme
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function getSiteThemePagesTemplateConfig($siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $theme = $this->siteManager->getSiteTheme($siteId);

        $rcmThemesConfig = $this->getThemeConfig($theme);

        if (empty($rcmThemesConfig['pages'])) {
            throw new RuntimeException(
                'No theme config found for site and no default theme found'
            );
        }

        return $rcmThemesConfig['pages'];
    }

    /**
     * Get Page Template method will attempt to locate and fetch the correct template
     * config for the page.  Default page template is set by the themes
     * configuration and can also be set per page.
     *
     * @param string|null  $template Template to find
     * @param integer|null $siteId   Site to lookup
     *
     * @return string
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function getSitePageTemplateConfig($template=null, $siteId=null)
    {
        $themePageConfig = $this->getSiteThemePagesTemplateConfig($siteId);

        if (!empty($themePageConfig[$template])) {
            return $themePageConfig[$template];

        } elseif (!empty($themePageConfig['default'])) {
            return $themePageConfig['default'];
        }

        throw new RuntimeException('No Page Template Found in config');
    }

    /**
     * Get Page Template method will attempt to locate and fetch the correct template
     * for the page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.  Default page template is set by the themes configuration and can
     * also be set per page.
     *
     * @param string|null  $template Template to find
     * @param integer|null $siteId   Site to lookup
     *
     * @return string
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function getSitePageTemplate($template=null, $siteId=null)
    {
        $themePageConfig = $this->getSitePageTemplateConfig($template, $siteId);

        if (empty($themePageConfig['file'])) {
            throw new RuntimeException('No Page Template Found in config');
        }

        return $themePageConfig['file'];
    }

    /**
     * Check to see if a layout is valid and available for a theme
     *
     * @param string  $layoutKey Layout name to search
     * @param integer $siteId    Site Id to use.  If none provided will use current
     *                           sites ID
     *
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function isLayoutValid($layoutKey, $siteId=null)
    {
        $themesConfig = $this->getSiteThemeLayoutsConfig($siteId);

        if (!empty($themesConfig[$layoutKey])) {
            return true;
        }

        return false;
    }

    /**
     * Get the main layout validator
     *
     * @return MainLayout
     */
    public function getMainLayoutValidator()
    {
        return new MainLayout($this);
    }
}