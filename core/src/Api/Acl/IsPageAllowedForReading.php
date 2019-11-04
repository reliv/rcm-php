<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Entity\Page;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsPageAllowedForReading
{
    /**
     * @param ServerRequestInterface $request
     * @param Page $page
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        Page $page
    ): bool;

    public function currentUserHasReadAccessToPageAccordingToAclSystem(array $groups): bool ;
}
