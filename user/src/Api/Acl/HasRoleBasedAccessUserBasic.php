<?php

namespace RcmUser\Api\Acl;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\User\Entity\UserInterface;

/**
 * NOTE: This does NOT use rules, just determines if the user has a role in the linage
 */
class HasRoleBasedAccessUserBasic implements HasRoleBasedAccessUser
{
    protected $authorizeService;

    /**
     * @param AuthorizeService $authorizeService
     */
    public function __construct(
        AuthorizeService $authorizeService
    ) {
        $this->authorizeService = $authorizeService;
    }

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
    ): bool {
        if (!($user instanceof UserInterface)) {
            return false;
        }

        return $this->authorizeService->hasRoleBasedAccess($user, $roleId, $useRoleInheritance);
    }
}
