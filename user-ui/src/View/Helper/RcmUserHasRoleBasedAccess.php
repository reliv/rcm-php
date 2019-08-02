<?php
namespace RcmUser\Ui\View\Helper;

use RcmUser\Acl\Service\AuthorizeService;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * @deprecated Use \RcmUser\Api\Acl\HasRoleBasedAccessUser
 * @author     James Jervis - https://github.com/jerv13
 */
class RcmUserHasRoleBasedAccess extends AbstractHelper
{
    /**
     * @var AuthorizeService
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
