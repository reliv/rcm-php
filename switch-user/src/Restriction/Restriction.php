<?php

namespace Rcm\SwitchUser\Restriction;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Restriction
{
    /**
     * allowed
     *
     * @param UserInterface $adminUser
     * @param UserInterface $targetUser
     *
     * @return Result
     */
    public function allowed(UserInterface $adminUser, UserInterface $targetUser);
}
