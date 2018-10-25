<?php

namespace Rcm\Acl;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ResourceNameRcm implements ResourceName
{
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
    ) {
        if (empty($root)) {
            $root = self::RESOURCE_SITES;
        }

        if (empty($siteId)) {
            return $root;
        }

        if (empty($pages)) {
            return $root . '.' . $siteId;
        }

        if (empty($pageType) || empty($pageName)) {
            return $root . '.' . $siteId . '.' . $pages;
        }

        return $root . '.' . $siteId . '.' . $pages . '.' . $pageType . '.' . $pageName;
    }

    /**
     * @param array $resources
     *
     * @return null|string
     */
    public function getFromArray(
        array $resources
    ) {
        $root = (empty($resources[0])) ? null : $resources[0];
        $siteId = (empty($resources[1])) ? null : $resources[1];
        $pages = (empty($resources[2])) ? null : $resources[2];
        $pageType = (empty($resources[3])) ? null : $resources[3];
        $pageName = (empty($resources[4])) ? null : $resources[4];

        return $this->get(
            $root,
            $siteId,
            $pages,
            $pageType,
            $pageName
        );
    }

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isSitesResourceId($resourceId)
    {
        $needle = ResourceProvider::RESOURCE_SITES . '.';
        $length = strlen($needle);

        return (substr($resourceId, 0, $length) === $needle);
    }

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isPagesResourceId($resourceId)
    {
        $resources = explode('.', $resourceId);

        return ($this->isSitesResourceId($resourceId) && !empty($resources[2])
            && $resources[2] === self::RESOURCE_PAGES);
    }

    /**
     * @param string $resourceId
     *
     * @return bool
     */
    public function isPageResourceId($resourceId)
    {
        $resources = explode('.', $resourceId);

        return (!empty($resources[3]) && !empty($resources[4]));
    }
}
