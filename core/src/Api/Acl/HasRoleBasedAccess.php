<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface HasRoleBasedAccess
{
    /**
     * @param ServerRequestInterface $request
     * @param string                 $roleId
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $roleId
    ):bool;
}
