<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetUserById
{
    /**
     * @param int|string $userId
     *
     * @return UserInterface|null
     */
    public function __invoke(
        $userId
    );
}
