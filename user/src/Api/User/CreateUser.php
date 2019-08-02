<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface CreateUser
{
    /**
     * @param UserInterface $requestUser
     *
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    );
}
