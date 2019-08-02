<?php

namespace RcmUser\Ui\Controller\Plugin;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * @deprecated Use \RcmUser\Api\Acl\HasRoleBasedAccessUser
 * @author     James Jervis - https://github.com/jerv13
 */
class RcmUserHasRoleBasedAccess extends AbstractPlugin
{

    /**
     * @var AuthorizeService $authorizeService
     */
    protected $authorizeService;

    /**
     * @var UserAuthenticationService $userAuthService
     */
    protected $userAuthService;

    /**
     * __construct
     *
     * @param AuthorizeService          $authorizeService authorizeService
     * @param UserAuthenticationService $userAuthService  userAuthService
     */
    public function __construct(
        AuthorizeService $authorizeService,
        UserAuthenticationService $userAuthService
    ) {
        $this->authorizeService = $authorizeService;
        $this->userAuthService = $userAuthService;
    }

    /**
     * @deprecated Use RcmUserService->hasRoleBasedAccess()
     * __invoke
     *
     * @param $roleId
     *
     * @return bool
     */
    public function __invoke(
        $roleId
    ) {
        $user = $this->userAuthService->getIdentity();

        if (!($user instanceof UserInterface)) {
            return false;
        }

        return $this->authorizeService->hasRoleBasedAccess($user, $roleId);
    }
}
