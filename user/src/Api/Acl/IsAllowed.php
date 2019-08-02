<?php

namespace RcmUser\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsAllowed
{
    /**
     * Check if the current Auth'd User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param ServerRequestInterface $request
     * @param string                 $resourceId
     * @param string|null            $privilege
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $resourceId,
        $privilege = null
    ):bool;
}
