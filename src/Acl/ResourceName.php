<?php

namespace Rcm\Acl;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ResourceName
{
    const RESOURCE_SITES = 'sites';

    const RESOURCE_PAGES = 'pages';

    /**
     * @param string|null $root
     * @param string|null $siteId
     * @param string|null $pages
     * @param string|null $pageType
     * @param string|null $pageName
     *
     * @return null|string
     */
    public function get(
        $root = null,
        $siteId = null,
        $pages = null,
        $pageType = null,
        $pageName = null
    );

    /**
     * @param array $resources
     *
     * @return null|string
     */
    public function getFromArray(
        array $resources
    );

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isSitesResourceId($resourceId);

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isPagesResourceId($resourceId);

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isPageResourceId($resourceId);
}
