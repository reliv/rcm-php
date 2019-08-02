<?php

namespace RcmUser\Api\Authentication;

use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ValidateCredentials
{
    /**
     * Allows the validation of user credentials (username and password)
     * without creating an auth session.
     * Helpful for doing non-login authentication checks.
     *
     * @param UserInterface $requestUser
     *
     * @return Result
     */
    public function __invoke(
        UserInterface $requestUser
    ): Result;
}
