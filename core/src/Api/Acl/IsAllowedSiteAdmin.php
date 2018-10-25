<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Site;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsAllowedSiteAdmin
{
    /**
     * @param ServerRequestInterface $request
     * @param Site                   $site
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Site $site
    ):bool;
}
