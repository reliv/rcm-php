<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DeleteUserBasic implements DeleteUser
{
    protected $deleteUserResult;

    /**
     * @param DeleteUserResult $deleteUserResult
     */
    public function __construct(
        DeleteUserResult $deleteUserResult
    ) {
        $this->deleteUserResult = $deleteUserResult;
    }

    /**
     * @param UserInterface $requestUser
     *
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    ) {
        $result = $this->deleteUserResult->__invoke(
            $requestUser
        );

        return $result->getData();
    }
}
