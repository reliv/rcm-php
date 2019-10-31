<?php

namespace Rcm\SwitchUser\Restriction;

use Rcm\SwitchUser\Acl\DoesAclSayUserCanSU;
use Rcm\SwitchUser\Service\SwitchUserAclService;
use RcmUser\User\Entity\UserInterface;

/**
 *
 * Class AclRestriction
 * @package Rcm\SwitchUser\Restriction
 */
class AclRestriction implements Restriction
{
    /**
     * @var DoesAclSayUserCanSU
     */
    protected $doesAclSayUserCanSU;

    public function __construct(DoesAclSayUserCanSU $doesAclSayUserCanSU)
    {
        $this->doesAclSayUserCanSU = $doesAclSayUserCanSU;
    }

    /**
     * Returns true if the admin user has permissions to SU
     *
     * @param UserInterface $adminUser
     * @param UserInterface $targetUser
     *
     * @return RestrictionResult
     */
    public function allowed(UserInterface $adminUser, UserInterface $targetUser)
    {
        if (!$this->doesAclSayUserCanSU->__invoke($adminUser)) {
            return new RestrictionResult(
                false,
                'Current user cannot switch user'
            );
        }

        return new RestrictionResult(true);
    }
}
