<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface UserExists
{
    /**
     * returns true if the user exists in the data source
     *
     * @param UserInterface $requestUser
     *
     * @return bool
     */
    public function __invoke(
        UserInterface $requestUser
    ): bool;
}
