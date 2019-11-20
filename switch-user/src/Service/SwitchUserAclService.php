<?php

namespace Rcm\SwitchUser\Service;

use Rcm\SwitchUser\Acl\DoesAclSayUserCanSU;
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

    public function __construct(
        DoesAclSayUserCanSU $doesAclSayUserCanSU,
        GetIdentity $getIdentity,
        SwitchUserService $switchUserService
    ) {
        $this->doesAclSayUserCanSU = $doesAclSayUserCanSU;
        $this->getIdentity = $getIdentity;
        $this->switchUserService = $switchUserService;
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
}
