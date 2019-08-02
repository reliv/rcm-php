<?php

namespace RcmUser\Api\Acl;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsUserAllowedBasic implements IsUserAllowed
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
     * @param null|UserInterface $user
     * @param string             $resourceId
     * @param null               $privilege
     *
     * @return bool
     * @throws \RcmUser\Acl\Exception\RcmUserAclException
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function __invoke(
        $user,
        $resourceId,
        $privilege = null
    ):bool {
        return $this->authorizeService->isAllowed(
            $resourceId,
            $privilege,
            null, // deprecated and not used
            $user
        );
    }
}
