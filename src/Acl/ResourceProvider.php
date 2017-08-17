<?php

namespace Rcm\Acl;

use Rcm\Entity\Page;
use Rcm\Entity\Site;
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
    const RESOURCE_SITES = ResourceName::RESOURCE_SITES;

    const RESOURCE_PAGES = ResourceName::RESOURCE_PAGES;

    /** @var string */
    protected $providerId = \Rcm\Acl\ResourceProvider::class;

    /** @var Site */
    protected $currentSite;

    /**
     * @var ResourceName
     */
    protected $resourceName;

    /**
     * @param array        $resources
     * @param Site         $currentSite
     * @param ResourceName $resourceName
     */
    public function __construct(
        array $resources,
        Site $currentSite,
        ResourceName $resourceName
    ) {
        $this->currentSite = $currentSite;
        $this->resourceName = $resourceName;
        parent::__construct(
            $resources
        );
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

        if (!$this->resourceName->isSitesResourceId($resourceId)) {
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
        // Page Resource Mapper
        $resource = $this->pageResourceMapper($resourceId);

        if (!empty($resource)) {
            return $resource;
        }

        $resource = $this->siteResourceMapper($resourceId);

        if (!empty($resource)) {
            return $resource;
        }

        return null;
    }

    /**
     * Page Resource Mapper
     *
     * @param string $resourceId Resource Id to search
     *
     * @return array|null
     */
    protected function pageResourceMapper($resourceId)
    {
        if (!$this->resourceName->isPagesResourceId($resourceId)) {
            return null;
        }

        $resources = explode('.', $resourceId);

        if (empty($resources[1])) {
            return null;
        }

        $siteResourceId = $this->resourceName->get(self::RESOURCE_SITES, $resources[1]);

        $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => $siteResourceId,
        ];

        if ($this->resourceName->isPageResourceId($resourceId)) {
            $pagesResourceId = $this->resourceName->get(self::RESOURCE_SITES, $resources[1], self::RESOURCE_PAGES);
            $return['parentResourceId'] = $pagesResourceId;
        }

        return array_merge(
            $this->resources[self::RESOURCE_PAGES],
            $return
        );
    }

    /**
     * Site Resource Mapper
     *
     * @param string $resourceId Resource Id to search
     *
     * @return array|null
     */
    protected function siteResourceMapper($resourceId)
    {
        if (!$this->resourceName->isSitesResourceId($resourceId)) {
            return null;
        }

        $sitesResourceId = $this->resourceName->get(self::RESOURCE_SITES);

        $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => $sitesResourceId,
        ];

        return array_merge(
            $this->resources[self::RESOURCE_SITES],
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

        $sitesResourceId = $this->resourceName->get(self::RESOURCE_SITES);
        $siteResourceId = $this->resourceName->get(self::RESOURCE_SITES, $siteId);

        $return[$siteResourceId] = [
            'resourceId' => $siteResourceId,
            'parentResourceId' => $sitesResourceId,
            'name' => $primaryDomainName,
            'description' => "Resource for site '{$primaryDomainName}'"
        ];

        $return[$siteResourceId] = array_merge(
            $this->resources[$sitesResourceId],
            $return[$siteResourceId]
        );

        $pagesResourceId = $this->resourceName->get(self::RESOURCE_SITES, $siteId, self::RESOURCE_PAGES);

        $return[$pagesResourceId] = [
            'resourceId' => $pagesResourceId,
            'parentResourceId' => $siteResourceId,
            'name' => $primaryDomainName . ' - pages',
            'description' => "Resource for pages on site '{$primaryDomainName}'"
        ];

        $return[$pagesResourceId] = array_merge(
            $this->resources[self::RESOURCE_PAGES],
            $return[$pagesResourceId]
        );

        $pages = $site->getPages();

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

        $pagesResourceId = $this->resourceName->get(self::RESOURCE_SITES, $siteId, self::RESOURCE_PAGES);
        $pageResourceId = $this->resourceName->get(
            self::RESOURCE_SITES,
            $siteId,
            self::RESOURCE_PAGES,
            $pageType,
            $pageName
        );

        $return[$pageResourceId]
            = [
            'resourceId' => $pageResourceId,
            'parentResourceId' => $pagesResourceId,
            'name' => $primaryDomainName . ' - pages - ' . $pageName,
            'description' => "Resource for page '{$pageName}'"
                . " of type '{$pageType}' on site '{$primaryDomainName}'"
        ];

        $return[$pageResourceId]
            = array_merge(
            $this->resources[self::RESOURCE_PAGES],
            $return[$pageResourceId]
        );

        return $return;
    }
}
