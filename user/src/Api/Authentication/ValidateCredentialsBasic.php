<?php

namespace RcmUser\Api\Authentication;

use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateCredentialsBasic implements ValidateCredentials
{
    protected $userAuthenticationService;

    /**
     * @param UserAuthenticationService $userAuthenticationService
     */
    public function __construct(
        UserAuthenticationService $userAuthenticationService
    ) {
        $this->userAuthenticationService = $userAuthenticationService;
    }

    /**
     * @param UserInterface $requestUser
     *
     * @return Result
     */
    public function __invoke(
        UserInterface $requestUser
    ): Result {
        return $this->userAuthenticationService->validateCredentials($requestUser);
    }
}
