<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UserExistsBasic implements UserExists
{
    protected $readUserResult;

    /**
     * @param ReadUserResult $readUserResult
     */
    public function __construct(
        ReadUserResult $readUserResult
    ) {
        $this->readUserResult = $readUserResult;
    }

    /**
     * @param UserInterface $requestUser
     *
     * @return bool
     */
    public function __invoke(
        UserInterface $requestUser
    ): bool {
        $result = $this->readUserResult->__invoke($requestUser);

        return $result->isSuccess();
    }
}
