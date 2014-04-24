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
     * Get Layout method will attempt to locate and fetch the correct layout
     * for the site and page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.
     *
     * @param string $layout Layout to find
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLayout($layout=null)
    {
        //Get Page Layout
        $config = $this->config['Rcm']['themes'];

        $theme = $this->siteManager->getCurrentSiteTheme();

        if (empty($layout)) {
            $layout = $this->siteManager->getCurrentSiteDefaultLayout();
        }

        if (!empty($config[$theme])
            && !empty($config[$theme]['layouts'])
            && !empty($config[$theme]['layouts'][$layout])
            && !empty($config[$theme]['layouts'][$layout]['file'])
        ) {
            return $config[$theme]['layouts'][$layout]['file'];

        } elseif (!empty($config[$theme])
            && !empty($config[$theme]['layouts'])
            && !empty($config[$theme]['layouts']['default'])
            && !empty($config[$theme]['layouts']['default']['file'])
        ) {
            return $config[$theme]['layouts']['default']['file'];

        } elseif (!empty($config['generic'])
            && !empty($config['generic']['layouts'])
            && !empty($config['generic']['layouts'][$layout])
            && !empty($config['generic']['layouts'][$layout]['file'])
        ) {
            return $config['generic']['layouts'][$layout]['file'];

        } elseif (!empty($config['generic'])
            && !empty($config['generic']['layouts'])
            && !empty($config['generic']['layouts']['default'])
            && !empty($config['generic']['layouts']['default']['file'])
        ) {
            return $config['generic']['layouts']['default']['file'];

        } else {
            throw new InvalidArgumentException('No Layouts Found in config');
        }
    }

    /**
     * Get Page Template method will attempt to locate and fetch the correct template
     * for the page.  If found it will pass back the path to correct
     * view template so that the indexAction can pass that value on to the
     * renderer.  Default page template is set by the themes configuration and can
     * also be set per page.
     *
     * @param null $template Template to find
     *
     * @return string
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getPageTemplate($template=null)
    {
        //Get Page Layout
        $config = $this->config['Rcm']['themes'];

        $theme = $this->siteManager->getCurrentSiteTheme();

        if (!empty($config[$theme])
            && !empty($config[$theme]['pages'])
            && !empty($config[$theme]['pages'][$template])
            && !empty($config[$theme]['pages'][$template]['file'])
        ) {
            return $config[$theme]['pages'][$template]['file'];

        } elseif (!empty($config[$theme])
            && !empty($config[$theme]['pages'])
            && !empty($config[$theme]['pages']['default'])
            && !empty($config[$theme]['pages']['default']['file'])
        ) {
            return $config[$theme]['pages']['default']['file'];

        } elseif (!empty($config['generic'])
            && !empty($config['generic']['pages'])
            && !empty($config['generic']['pages'][$template])
            && !empty($config['generic']['pages'][$template]['file'])
        ) {
            return $config['generic']['pages'][$template]['file'];

        } elseif (!empty($config['generic'])
            && !empty($config['generic']['pages'])
            && !empty($config['generic']['pages']['default'])
            && !empty($config['generic']['pages']['default']['file'])
        ) {
            return $config['Rcm']['themes']['generic']['pages']['default']['file'];

        } else {
            throw new InvalidArgumentException('No Page Template Found in config');
        }
    }
}