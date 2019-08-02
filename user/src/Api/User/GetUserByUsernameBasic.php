<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserByUsernameBasic implements GetUserByUsername
{
    protected $buildNewUser;
    protected $getUser;

    /**
     * @param BuildNewUser $buildNewUser
     * @param GetUser      $getUser
     */
    public function __construct(
        BuildNewUser $buildNewUser,
        GetUser $getUser
    ) {
        $this->buildNewUser = $buildNewUser;
        $this->getUser = $getUser;
    }

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function __invoke(
        $username
    ) {
        $requestUser = $this->buildNewUser->__invoke();
        $requestUser->setUsername($username);

        return $this->getUser->__invoke($requestUser);
    }
}
