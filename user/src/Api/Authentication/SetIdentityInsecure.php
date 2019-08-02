<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;

/**
 * WARNING: This will authenticate WITH validating credentials
 *          Use with CAUTION
 *
 * @author James Jervis - https://github.com/jerv13
 */
class SetIdentityInsecure implements SetIdentity
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
     * @param UserInterface          $identity
     *
     * @return void
     * @throws RcmUserException
     */
    public function __invoke(
        ServerRequestInterface $request,
        UserInterface $identity
    ) {
        $this->userAuthenticationService->setIdentity($identity);
    }
}
