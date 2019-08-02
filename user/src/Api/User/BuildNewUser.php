<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BuildNewUser
{
    /**
     * @param array $options
     *
     * @return UserInterface
     */
    public function __invoke(
        array $options = []
    ): UserInterface;
}
