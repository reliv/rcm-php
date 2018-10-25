<?php

namespace Rcm\Api\Acl;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsPageRestricted
{
    /**
     * @param int|string  $siteId
     * @param string      $pageType
     * @param string      $pageName
     * @param string|null $privilege
     *
     * @return bool
     */
    public function __invoke(
        $siteId,
        string $pageType,
        string $pageName,
        $privilege
    ):bool;
}
