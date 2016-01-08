<?php

namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
use Rcm\Repository\Site as SiteRepo;
use RcmUser\Acl\Provider\ResourceProvider as RcmUserResourceProvider;

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
    protected $providerId = 'Rcm\Acl\ResourceProvider';

    /** @var \Rcm\Repository\Site */
    protected $siteRepo;

    /** @var Site */
    protected $currentSite;

    /**
     * @var array
     */
    protected $validResourceParts
        = [
            'sites',
            'pages',
        ];

    /**
     * ResourceProvider constructor.
     *
     * @param array    $resources
     * @param SiteRepo $siteRepo
     * @param Site     $currentSite
     */
    public function __construct(
        Array         $resources,
        SiteRepo $siteRepo,
        Site $currentSite
    ) {
        $this->resources = $resources;
        $this->siteRepo = $siteRepo;
        $this->currentSite = $currentSite;
    }

    /**
     * setProviderId
     *
     * @param string $providerId providerId
     *
     * @return void
     * @SuppressWarnings(PHPMD)
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

        // We will only expose the resources for the current site
        $return = array_merge($this->getSiteResources($this->currentSite), $return);

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
     * hasResource
     *
     * @param string $resourceId
     *
     * @return bool
     */
    public function hasResource($resourceId)
    {
        if (array_key_exists($resourceId, $this->resources)) {
            return true;
        }

        $resources = explode('.', $resourceId);

        $validDyn = array_intersect($resources, $this->validResourceParts);

        if(!empty($validDyn)) {
            return false;
        }

        // @todo this can be made more efficient
        $resource = $this->getResource($resourceId);

        return ($resource !== null);
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
            || $resources[2] != 'pages'
        ) {
            return null;
        }

        $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => 'sites.' . $resources[1],
        ];

        if (!empty($resources[3])
            && !empty($resources[4])
        ) {
            $return['parentResourceId'] = 'sites.' . $resources[1] . '.pages';
        }

        return array_merge(
            $this->resources['pages'],
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
            || $resources[0] != 'sites'
        ) {
            return null;
        }

        $return = $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => 'sites',
        ];

        return array_merge(
            $this->resources['sites'],
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
        $primaryDomain = $site->getDomain();

        if (empty($primaryDomain)) {
            // no resources if domain missing
            return array();
        }

        $primaryDomainName = $primaryDomain->getDomainName();
        $siteId = $site->getSiteId();

        $return['sites.' . $siteId] = [
            'resourceId' => 'sites.' . $siteId,
            'parentResourceId' => 'sites',
            'name' => $primaryDomainName
        ];

        $return['sites.' . $siteId] = array_merge(
            $this->resources['sites'],
            $return['sites.' . $siteId]
        );

        $return['sites.' . $siteId . '.pages'] = [
            'resourceId' => 'sites.' . $siteId . '.pages',
            'parentResourceId' => 'sites.' . $siteId,
            'name' => $primaryDomainName . ' - pages',
        ];

        $return['sites.' . $siteId . '.pages'] = array_merge(
            $this->resources['pages'],
            $return['sites.' . $siteId . '.pages']
        );

        $pages = $site->getpages();

        /** @var \Rcm\Entity\Page $page */
        foreach ($pages as &$page) {
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
        $pageType = $page->getPageType();

        $return['sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName] = [
            'resourceId' => 'sites.' . $siteId . '.pages.' . $pageType . '.'
                . $pageName,
            'parentResourceId' => 'sites.' . $siteId . '.pages',
            'name' => $primaryDomainName . ' - pages - ' . $pageName,
        ];

        $return['sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName]
            = array_merge(
                $this->resources['pages'],
                $return['sites.' . $siteId . '.pages.' . $pageType . '.' . $pageName]
            );

        return $return;
    }
}
