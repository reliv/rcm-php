<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HasIdentityBasic implements HasIdentity
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
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request
    ): bool {
        return $this->userAuthenticationService->hasIdentity();
    }
}
