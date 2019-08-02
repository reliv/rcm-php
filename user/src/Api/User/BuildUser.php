<?php

namespace RcmUser\Api\User;

use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface BuildUser
{
    /**
     * @param UserInterface $user
     * @param array         $options
     *
     * @return UserInterface
     * @throws RcmUserException
     */
    public function __invoke(
        UserInterface $user,
        array $options = []
    ): UserInterface;
}
