<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsAllowedShowRevisions
{
    /**
     * @param ServerRequestInterface $request
     * @param int|string             $siteId
     * @param string                 $pageType
     * @param string                 $pageName
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $siteId,
        string $pageType,
        string $pageName
    ): bool;
}
