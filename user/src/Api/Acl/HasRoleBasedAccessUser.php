<?php

namespace RcmUser\Api\Acl;

use RcmUser\User\Entity\UserInterface;

/**
 * NOTE: This does NOT use rules, just determines if the user has a role in the linage
 */
interface HasRoleBasedAccessUser
{
    /**
     * @param UserInterface|null $user
     * @param string $roleId
     *
     * @param $user
     * @param $roleId
     * @param bool $useRoleInheritance True means check the user's parent roles too
     * @return bool
     */
    public function __invoke(
        $user,
        $roleId,
        $useRoleInheritance = true
    ): bool;
}
