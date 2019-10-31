<?php

namespace Rcm\SwitchUser\Restriction;

use Rcm\SwitchUser\Acl\DoesAclSayUserCanSU;
use RcmUser\User\Entity\UserInterface;

class SuUserRestriction implements Restriction
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
     * Returns true if the target user does NOT have permissions to SU.
     * This effectivly enforces "you cannot SU to someone who can SU".
     *
     * @param UserInterface $adminUser
     * @param UserInterface $targetUser
     *
     * @return RestrictionResult
     */
    public function allowed(UserInterface $adminUser, UserInterface $targetUser)
    {
        if ($this->doesAclSayUserCanSU->__invoke($targetUser)) {
            return new RestrictionResult(false, 'Cannot switch to this user');
        }

        return new RestrictionResult(true);
    }
}
