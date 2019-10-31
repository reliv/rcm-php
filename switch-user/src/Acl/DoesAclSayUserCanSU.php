<?php

namespace Rcm\SwitchUser\Acl;

use Rcm\Acl\IsAllowedByUser;

class DoesAclSayUserCanSU
{
    protected $isAllowedByUser;

    public function __construct(IsAllowedByUser $isAllowedByUser)
    {
        $this->isAllowedByUser = $isAllowedByUser;
    }

    /**
     * Returns true if ACL says given user can SU. This does not mean the user should be allowed to though as there
     * are other restrictions other than ACL that must be taken into account before allowing a user to SU.
     *
     * @return bool
     */
    public function __invoke($user): bool
    {
        return $this->isAllowedByUser->__invoke('execute', ['type' => 'switchUser'], $user);
    }
}
