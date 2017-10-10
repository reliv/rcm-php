<?php

namespace Rcm\Api\Repository\Page;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AllowDuplicateForPageType
{
    const PAGE_TYPE_DELETED = 'deleted-';

    /**
     * @param string $pageType
     *
     * @return bool
     */
    public function __invoke($pageType)
    {
        return (strpos($pageType, self::PAGE_TYPE_DELETED) !== false);
    }
}
