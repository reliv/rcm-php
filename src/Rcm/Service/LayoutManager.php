<?php

namespace Rcm\Service;

use Rcm\Exception\InvalidArgumentException;

class LayoutManager
{
    protected $siteManager;
    protected $config;

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
     * @param string $layout
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getLayout($layout=null)
    {
        //Get Page Layout
        $config = $this->config;

        $theme = $this->siteManager->getCurrentSiteTheme();

        if (empty($layout)) {
            $layout = $this->siteManager->getCurrentSiteDefaultLayout();
        }

        if (!empty($config['Rcm']['themes'][$theme]['layouts'][$layout]['file'])) {
            return $config['Rcm']['themes'][$theme]['layouts'][$layout]['file'];
        } elseif (!empty($config['Rcm']['themes'][$theme]['layouts']['default']['file'])) {
            return $config['Rcm']['themes'][$theme]['layouts']['default']['file'];
        } elseif (!empty($config['Rcm']['themes']['generic']['layouts'][$layout]['file'])) {
            return $config['Rcm']['themes']['generic']['layouts'][$layout]['file'];
        } elseif (
        !empty($config['Rcm']['themes']['generic']['layouts']['default']['file'])
        ) {
            return $config['Rcm']['themes']['generic']['layouts']['default']['file'];
        } else {
            throw new InvalidArgumentException('No Layouts Found in config');
        }
    }

    public function getPageTemplate($template=null)
    {
        //Get Page Layout
        $config = $this->config;

        $theme = $this->siteManager->getCurrentSiteTheme();

        if (empty($layout)) {
            $layout = $this->siteManager->getCurrentSiteDefaultLayout();
        }

        if (!empty($config['Rcm']['themes'][$theme]['pages'][$layout]['file'])) {
            return $config['Rcm']['themes'][$theme]['pages'][$layout]['file'];
        } elseif (!empty($config['Rcm']['themes'][$theme]['pages']['default']['file'])) {
            return $config['Rcm']['themes'][$theme]['pages']['default']['file'];
        } elseif (!empty($config['Rcm']['themes']['generic']['pages'][$layout]['file'])) {
            return $config['Rcm']['themes']['generic']['pages'][$layout]['file'];
        } elseif (
        !empty($config['Rcm']['themes']['generic']['pages']['default']['file'])
        ) {
            return $config['Rcm']['themes']['generic']['pages']['default']['file'];
        } else {
            throw new InvalidArgumentException('No Page Template Found in config');
        }
    }
}