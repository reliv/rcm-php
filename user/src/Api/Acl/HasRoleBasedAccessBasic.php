<?php

namespace RcmUser\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\User\Entity\UserInterface;

/**
 * WARNING: This is not tested and my not work correctly
 * NOTE: This does NOT use rules, just determines if the user has a role in the linage
 */
class HasRoleBasedAccessBasic implements HasRoleBasedAccess
{
    protected $getIdentity;
    protected $hasRoleBasedAccessUser;

    /**
     * @param GetIdentity $getIdentity
     * @param HasRoleBasedAccessUser $hasRoleBasedAccessUser
     */
    public function __construct(
        GetIdentity $getIdentity,
        HasRoleBasedAccessUser $hasRoleBasedAccessUser
    ) {
        $this->getIdentity = $getIdentity;
        $this->hasRoleBasedAccessUser = $hasRoleBasedAccessUser;
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $roleId
     * @param $useRoleInheritance True means check the user's parent roles too
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        $roleId,
        $useRoleInheritance = true
    ): bool {
        $user = $this->getIdentity->__invoke($request);

        if (!($user instanceof UserInterface)) {
            return false;
        }

        return $this->hasRoleBasedAccessUser->__invoke($user, $roleId, $useRoleInheritance);
    }
}
