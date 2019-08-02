<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetUser
{
    /**
     * returns a user from the data source
     * based on the data in the provided User object (User::id and User::username)
     *
     * @param UserInterface $requestUser
     *
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    );
}
