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
    const RESOURCE_SITES = 'sites';

    const RESOURCE_PAGES = 'pages';

    /** @var string */
    protected $providerId = \Rcm\Acl\ResourceProvider::class;

    /** @var Site */
    protected $currentSite;

    /**
     * ResourceProvider constructor.
     *
     * @param array $resources
     * @param Site $currentSite
     */
    public function __construct(
        array $resources,
        Site $currentSite
    ) {
        $this->currentSite = $currentSite;
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

        if (!$this->startsWith($resourceId, self::RESOURCE_SITES . '.')) {
            return false;
        }

        // @todo this can be made more efficient
        $resource = $this->getResource($resourceId);

        return ($resource !== null);
    }

    /**
     * startsWith
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
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
     * @param array $resources Resources parsed from Resource Id
     *
     * @return array|null
     */
    protected function pageResourceMapper($resourceId, $resources)
    {
        if (empty($resources[2])
            || $resources[2] != self::RESOURCE_PAGES
        ) {
            return null;
        }

        $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => self::RESOURCE_SITES . '.' . $resources[1],
        ];

        if (!empty($resources[3])
            && !empty($resources[4])
        ) {
            $return['parentResourceId'] = self::RESOURCE_SITES . '.' . $resources[1] . '.' . self::RESOURCE_PAGES;
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
     * @param array $resources Resources parsed from Resource Id
     *
     * @return array|null
     */
    protected function siteResourceMapper($resourceId, $resources)
    {
        if (empty($resources[0])
            || $resources[0] != self::RESOURCE_SITES
        ) {
            return null;
        }

        $return = [
            'resourceId' => $resourceId,
            'parentResourceId' => self::RESOURCE_SITES,
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

        $return[self::RESOURCE_SITES . '.' . $siteId] = [
            'resourceId' => self::RESOURCE_SITES . '.' . $siteId,
            'parentResourceId' => self::RESOURCE_SITES,
            'name' => $primaryDomainName,
            'description' => "Resource for site '{$primaryDomainName}'"
        ];

        $return['sites.' . $siteId] = array_merge(
            $this->resources[self::RESOURCE_SITES],
            $return[self::RESOURCE_SITES . '.' . $siteId]
        );

        $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES] = [
            'resourceId' => self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES,
            'parentResourceId' => self::RESOURCE_SITES . '.' . $siteId,
            'name' => $primaryDomainName . ' - pages',
            'description' => "Resource for pages on site '{$primaryDomainName}'"
        ];

        $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES] = array_merge(
            $this->resources[self::RESOURCE_PAGES],
            $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES]
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

        $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES . '.' . $pageType . '.' . $pageName]
            = [
            'resourceId' => self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES . '.' . $pageType . '.'
                . $pageName,
            'parentResourceId' => self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES,
            'name' => $primaryDomainName . ' - pages - ' . $pageName,
            'description' => "Resource for page '{$pageName}'"
                . " of type '{$pageType}' on site '{$primaryDomainName}'"
            ];

        $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES . '.' . $pageType . '.' . $pageName]
            = array_merge(
                $this->resources[self::RESOURCE_PAGES],
                $return[self::RESOURCE_SITES . '.' . $siteId . '.' . self::RESOURCE_PAGES . '.' . $pageType . '.'
                . $pageName]
            );

        return $return;
    }
}
