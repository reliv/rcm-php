<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetUserBasic implements GetUser
{
    /**
     * @var ReadUser
     */
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
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    ) {
        $result = $this->readUserResult->__invoke($requestUser);

        if ($result->isSuccess()) {
            return $result->getUser();
        }

        return null;
    }
}
