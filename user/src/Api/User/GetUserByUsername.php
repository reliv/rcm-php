<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface GetUserByUsername
{
    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function __invoke(
        $username
    );
}
