<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ClearIdentityBasic implements ClearIdentity
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
     * @return void
     */
    public function __invoke(
        ServerRequestInterface $request
    ) {
        // NOTE: $request is not used, but it should
        $this->userAuthenticationService->clearIdentity();
    }
}
