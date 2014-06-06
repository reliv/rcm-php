<?php
/**
 * Acl Resource Provider
 *
 * This file contains the resource provider for RcmUser to be used by the CMS
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */
namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Service\PageManager;
use Rcm\Service\PluginManager;
use Rcm\Service\SiteManager;
use \RcmUser\Acl\Provider\ResourceProvider as RcmUserResourceProvider;

/**
 * Acl Resource Provider
 *
 * Resource provider for RcmUser to be used by the CMS
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ResourceProvider extends RcmUserResourceProvider
{
    /** @var string */
    protected $providerId = '\Rcm\Acl\ResourceProvider';

    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \Rcm\Service\PluginManager  */
    protected $pluginManager;

    /**
     * Constructor
     *
     * @param array         $resources     Config array of RCM resources
     * @param SiteManager   $siteManager   Rcm Site Manager
     * @param PageManager   $pageManager   Rcm Page Manager
     * @param PluginManager $pluginManager Rcm Plugin Manager
     */
    public function __construct(
        Array         $resources,
        SiteManager   $siteManager,
        PageManager   $pageManager,
        PluginManager $pluginManager
    ) {

        $this->resources     = $resources;
        $this->siteManager   = $siteManager;
        $this->pageManager   = $pageManager;
        $this->pluginManager = $pluginManager;
    }

    /**
     * setProviderId
     *
     * @param string $providerId providerId
     *
     * @return void
     */
    public function setProviderId($providerId)
    {
        return;
    }

    /**
     * getResources (ALL resources)
     * Return a multi-dimensional array of resources and privileges
     * containing ALL possible resources including run-time resources
     *
     * @return array
     */
    public function getResources()
    {
        $return = $this->resources;

        $sites = $this->siteManager->getAllActiveSites();

        foreach ($sites as $site) {
            $return = array_merge($this->getSiteResources($site), $return);
        }

        return $return;

    }

    /**
     * getResource
     * Return the requested resource
     * Can be used to return resources dynamically at run-time
     *
     * @param string $resourceId resourceId
     *
     * @return array|null
     */
    public function getResource($resourceId)
    {
        if (isset($this->resources[$resourceId])) {
            return $this->resources[$resourceId];
        }

        $dynamicResource = $this->dynamicResourceMapper($resourceId);

        if (!empty($dynamicResource)) {
            return $dynamicResource;
        }

        return null;
    }

    /**
     * Dynamic Resource Mapper for Get Resource
     *
     * @param string $resourceId Dynamic Resource ID to generate
     *
     * @return array|null
     */
    protected function dynamicResourceMapper($resourceId)
    {
        $resources = explode('.', $resourceId);

        // Page Resource Mapper
        $resource = $this->pageResourceMapper($resourceId, $resources);

        if (!empty($resource)) {
            return $resource;
        }

        $resource = $this->siteResourceMapper($resourceId, $resources);

        if (!empty($resource)) {
            return $resource;
        }

        return null;
    }

    /**
     * Page Resource Mapper
     *
     * @param string $resourceId Resource Id to search
     * @param array  $resources  Resources parsed from Resource Id
     *
     * @return array|null
     */
    protected function pageResourceMapper($resourceId, $resources)
    {
        if (empty($resources[2])
            || $resources[2] != 'Pages'
        ) {
            return null;
        }

        $return = array(
            'resourceId' => $resourceId,
            'parentResourceId' => 'Sites.'.$resources[1],
        );

        if (!empty($resources[3])) {
            $return['parentResourceId'] = 'Sites.'.$resources[1].'.Pages';
        }

        return array_merge(
            $this->resources['Pages'],
            $return
        );
    }

    /**
     * Site Resource Mapper
     *
     * @param string $resourceId Resource Id to search
     * @param array  $resources  Resources parsed from Resource Id
     *
     * @return array|null
     */
    protected function siteResourceMapper($resourceId, $resources)
    {
        if (empty($resources[0])
            || $resources[0] != 'Sites'
        ) {
            return null;
        }

        $return = $return = array(
            'resourceId' => $resourceId,
            'parentResourceId' => 'Sites',
        );

        return array_merge(
            $this->resources['Sites'],
            $return
        );
    }

    /**
     * Get all resources for a site
     *
     * @param Site $site Rcm Site Entity
     *
     * @return array
     */
    protected function getSiteResources(Site $site)
    {
        $primaryDomainName = $site->getDomain()->getDomainName();
        $siteId = $site->getSiteId();

        $return['Sites.'.$siteId] = array(
            'resourceId' => 'Sites.'.$siteId,
            'parentResourceId' => 'Sites',
            'name' => $primaryDomainName
        );

        $return['Sites.'.$siteId] = array_merge(
            $this->resources['Sites'],
            $return['Sites.'.$siteId]
        );

        $return['Sites.'.$siteId.'.Pages'] = array(
            'resourceId' => 'Sites.'.$siteId.'.Pages',
            'parentResourceId' => 'Sites.'.$siteId,
            'name' => $primaryDomainName.' - Pages',
        );

        $return['Sites.'.$siteId.'.Pages'] = array_merge(
            $this->resources['Pages'],
            $return['Sites.'.$siteId.'.Pages']
        );

        $pages = $site->getPages();

        /** @var \Rcm\Entity\Page $page */
        foreach ($pages as $page) {
            $pageResources = $this->getPageResources($page, $site);
            $return = array_merge($pageResources, $return);
        }

        return $return;
    }

    /**
     * Get all Page Resources
     *
     * @param Page $page Rcm Page Entity
     * @param Site $site Rcm Site Entity
     *
     * @return mixed
     */
    protected function getPageResources(Page $page, Site $site)
    {
        $primaryDomainName = $site->getDomain()->getDomainName();
        $siteId = $site->getSiteId();
        $pageName = $page->getName();

        $return['Sites.'.$siteId.'.Pages.'.$pageName] = array(
            'resourceId' => 'Sites.'.$siteId.'.Pages.'.$pageName,
            'parentResourceId' => 'Sites.'.$siteId.'.Pages',
            'name' => $primaryDomainName.' - Pages - '.$pageName,
        );

        $return['Sites.'.$siteId.'.Pages.'.$pageName] = array_merge(
            $this->resources['Pages'],
            $return['Sites.'.$siteId.'.Pages.'.$pageName]
        );

        return $return;
    }
}
