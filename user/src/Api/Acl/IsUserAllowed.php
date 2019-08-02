<?php

namespace RcmUser\Api\Acl;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsUserAllowed
{
    /**
     * Check if the supplied User has
     * access to a resource with a privilege provided by provider id.
     * This is use to validate a users access
     * based on their role and the rules set by ACL
     *
     * @param UserInterface|null $user
     * @param string             $resourceId
     * @param string|null        $privilege
     *
     * @return bool
     */
    public function __invoke(
        $user,
        $resourceId,
        $privilege = null
    ):bool;
}
