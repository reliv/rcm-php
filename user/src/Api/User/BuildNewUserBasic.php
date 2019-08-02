<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;
use RcmUser\User\Entity\User;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class BuildNewUserBasic implements BuildNewUser
{
    protected $buildUser;

    /**
     * @param BuildUser $buildUser
     */
    public function __construct(
        BuildUser $buildUser
    ) {
        $this->buildUser = $buildUser;
    }

    /**
     * @param array $options
     *
     * @return UserInterface
     */
    public function __invoke(
        array $options = []
    ): UserInterface {
        $user = new User();

        return $this->buildUser->__invoke($user);
    }
}
