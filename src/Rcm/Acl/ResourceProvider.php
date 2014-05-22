<?php

namespace Rcm\Acl;

use Rcm\Entity\Site;
use Rcm\Service\PageManager;
use Rcm\Service\PluginManager;
use Rcm\Service\SiteManager;
use RcmUser\Acl\Entity\AclResource;
use RcmUser\Acl\Provider\ResourceProviderInterface;

class ResourceProvider implements ResourceProviderInterface
{
    /** @var array */
    protected $config = array();

    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /** @var \Rcm\Service\PageManager  */
    protected $pageManager;

    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    protected $runtimeTree;

    /**
     * Constructor
     *
     * @param Array         $config        Rcm Acl Config Array
     * @param SiteManager   $siteManager   Rcm Site Manager
     * @param PageManager   $pageManager   Rcm Page Manager
     * @param PluginManager $pluginManager Rcm Plugin Manager
     */
    public function __construct(
        $config,
        SiteManager $siteManager,
        PageManager $pageManager,
        PluginManager $pluginManager
    ) {
        $this->config = $config;
        $this->siteManager = $siteManager;
        $this->pageManager = $pageManager;
        $this->pluginManager = $pluginManager;
    }

    /**
     * getResources
     * Return a multi-dimensional array of resources and privileges
     * containing ALL possible resources
     *
     * @return array
     */
    public function getResources()
    {

        $siteI
        return $sites;

    }

    /**
     * getResource
     * Return the requested resource
     * Can be used to return resources dynamically.
     *
     * @param string $resourceId Resource Id
     *
     * @return array
     */
    public function getResource($resourceId)
    {

    }

    /**
     * Get ALL Site Resources
     *
     * @param \Rcm\Entity\Site $site
     *
     * @return array
     */
    protected function getSiteResource(Site $site)
    {
        $return = array();

        $siteId = $site->getSiteId();
        $siteDomain = $site->getDomain()->getDomainName();

        return array(
            $this->resourceTemplate(
                'Site.'.$siteId,
                'Sites',
                array(
                    'read',
                    'update',
                    'create',
                    'delete',
                    'theme',
                ),
                $siteDomain,
                'Resource for '.$siteDomain
            ),
        );
    }

    protected function getPageRootResource($siteId)
    {
        return $this->resourceTemplate(
            'Site.'.$siteId'.Pages',
            'Site.'.$siteId,

        )
    }

    protected function resourceTemplate(
        $resourceId,
        $parentResourceId,
        $privileges,
        $name,
        $desc
    ) {
        return array(
            'resourceId' => $resourceId,
            'parentResourceId' => $parentResourceId,
            'privileges' => $privileges,
            'name' => $name,
            'description' => $desc,
        );
    }


}