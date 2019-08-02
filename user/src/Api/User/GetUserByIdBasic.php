<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserByIdBasic implements GetUserById
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
     * @param int|string $userId
     *
     * @return UserInterface|null
     */
    public function __invoke(
        $userId
    ) {
        $requestUser = $this->buildNewUser->__invoke();
        $requestUser->setId($userId);

        return $this->getUser->__invoke($requestUser);
    }
}
