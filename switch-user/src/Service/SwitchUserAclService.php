<?php

namespace Rcm\SwitchUser\Service;

use Rcm\Acl\IsAllowedByUser;
use Rcm\SwitchUser\Acl\DoesAclSayUserCanSU;
use RcmUser\Api\Acl\IsUserAllowed;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\GetPsrRequest;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SwitchUserAclService
{
    /**
     * @var DoesAclSayUserCanSU
     */
    protected $doesAclSayUserCanSU;

    /**
     * @var GetIdentity
     */
    protected $getIdentity;

    /**
     * @var SwitchUserService
     */
    protected $switchUserService;

    protected $isUserAllowed;

    public function __construct(
        DoesAclSayUserCanSU $doesAclSayUserCanSU,
        GetIdentity $getIdentity,
        SwitchUserService $switchUserService,
        IsUserAllowed $isUserAllowed //This should be removed eventually as it uses the OLD ACL system
    ) {
        $this->doesAclSayUserCanSU = $doesAclSayUserCanSU;
        $this->getIdentity = $getIdentity;
        $this->switchUserService = $switchUserService;
        $this->isUserAllowed = $isUserAllowed;
    }

    /**
     * getAclUser
     *
     * @param $user
     *
     * @return mixed|null
     */
    public function getAclUser($user)
    {
        if (empty($user)) {
            return null;
        }

        $adminUser = $this->switchUserService->getImpersonatorUser($user);
        $targetUser = $user;

        if (empty($adminUser)) {
            $adminUser = $targetUser;
        }

        return $adminUser;
    }

    /**
     * isImpersonatorUserAllowed
     *
     * @param string $resourceId
     * @param string $privilege
     * @param null $providerId // @deprecated
     * @param UserInterface $user
     *
     * @return bool|mixed
     */
    public function isImpersonatorUserAllowed(
        $resourceId,
        $privilege,
        $user,
        $providerId = null
    ) {
        $user = $this->switchUserService->getImpersonatorUser($user);

        if (empty($user)) {
            return false;
        }

        return $this->isUserAllowed->__invoke(
            $user,
            $resourceId,
            $privilege
        );
    }

    /**
     * isCurrentImpersonatorUserAllowed
     *
     * @param $resourceId
     * @param $privilege
     * @param $providerId
     *
     * @return bool|mixed
     */
    public function isCurrentImpersonatorUserAllowed(
        $resourceId,
        $privilege,
        $providerId = null
    ) {
        $psrRequest = GetPsrRequest::invoke();

        $user = $this->getIdentity->__invoke($psrRequest);

        return $this->isImpersonatorUserAllowed(
            $resourceId,
            $privilege,
            $user,
            $providerId
        );
    }

    /**
     * isSuAllowed
     *
     * this is only a basic access check,
     * the restrictions should catch and log any access attempts
     *
     * @param $suUser
     *
     * @return bool|mixed
     */
    public function isSuAllowed($suUser)
    {
        return $this->doesAclSayUserCanSU->__invoke($suUser);
    }

    /**
     * @deprecated USES OLD ACL SYSTEM THAT WILL BE REMOVED!
     *
     * This is here for BC support of a closed source conference registration system and should be removed eventually.
     *
     * This runs an ACL check against the user who SU'd if they exist, otherwise it runs against the current user.
     *
     * @param $resourceId
     * @param $privilege
     * @param null $providerId
     * @return mixed
     */
    public function currentUserIsAllowed($resourceId, $privilege, $providerId = null)
    {
        $checkUser = $this->getIdentity->__invoke(GetPsrRequest::invoke());

        $adminUser = $this->getAclUser($checkUser);

        if (!empty($adminUser)) {
            $checkUser = $adminUser;
        }

        return $this->isUserAllowed->__invoke(
            $checkUser,
            $resourceId,
            $privilege
        );
    }
}
