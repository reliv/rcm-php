<?php

namespace RcmUser\Api\User;

use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class UpdateUserBasic implements UpdateUser
{
    protected $updateUserResult;

    /**
     * @param UpdateUserResult $updateUserResult
     */
    public function __construct(
        UpdateUserResult $updateUserResult
    ) {
        $this->updateUserResult = $updateUserResult;
    }

    /**
     * @param UserInterface $requestUser
     *
     * @return UserInterface|null
     */
    public function __invoke(
        UserInterface $requestUser
    ) {
        $result = $this->updateUserResult->__invoke(
            $requestUser
        );

        return $result->getData();
    }
}
