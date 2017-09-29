<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasRoleBasedAccessBasic implements HasRoleBasedAccess
{
    protected $hasRoleBasedAccess;

    /**
     * @param \RcmUser\Api\Acl\HasRoleBasedAccess $hasRoleBasedAccess
     */
    public function __construct(
        \RcmUser\Api\Acl\HasRoleBasedAccess $hasRoleBasedAccess
    ) {
        $this->hasRoleBasedAccess = $hasRoleBasedAccess;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string                 $roleId
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $roleId
    ):bool {
        return $this->hasRoleBasedAccess->__invoke(
            $request,
            $roleId
        );
    }
}
