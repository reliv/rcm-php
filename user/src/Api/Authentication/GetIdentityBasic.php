<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetIdentityBasic implements GetIdentity
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
     * @param ServerRequestInterface $request
     * @param null                   $default
     *
     * @return UserInterface|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        $default = null
    ) {
        // NOTE: $request is not used, but it should
        return $this->userAuthenticationService->getIdentity($default);
    }
}
