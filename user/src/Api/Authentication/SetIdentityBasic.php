<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\Exception\RcmUserException;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SetIdentityBasic implements SetIdentity
{
    protected $getIdentity;
    protected $userAuthenticationService;

    /**
     * @param GetIdentity               $getIdentity
     * @param UserAuthenticationService $userAuthenticationService
     */
    public function __construct(
        GetIdentity $getIdentity,
        UserAuthenticationService $userAuthenticationService
    ) {
        $this->getIdentity = $getIdentity;
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
        $currentUser = $this->getIdentity->__invoke($request);

        if (empty($currentUser) || $identity->getId() !== $currentUser->getId()) {
            throw new RcmUserException(
                'SetIdentity expects user to be get same identity as current, '
                . 'user authenticate to change users.'
            );
        }

        $this->userAuthenticationService->setIdentity($identity);
    }
}
