<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class CreateUserBasic implements CreateUser
{
    protected $createUserResult;

    /**
     * @param CreateUserResult $createUserResult
     */
    public function __construct(
        CreateUserResult $createUserResult
    ) {
        $this->createUserResult = $createUserResult;
    }

    /**
     * @param UserInterface $requestUser
     *
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    ) {
        $result = $this->createUserResult->__invoke(
            $requestUser
        );

        return $result->getData();
    }
}
