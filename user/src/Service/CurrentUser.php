<?php

namespace RcmUser\Service;

use RcmUser\Authentication\Service\UserAuthenticationService;
use RcmUser\User\Entity\UserInterface;

/**
 * @deprecated Use RcmUser\Api\Authentication\GetIdentity
 *
 * @author James Jervis - https://github.com/jerv13
 */
class CurrentUser
{
    /**
     * @var
     */
    protected $authenticationService;

    /**
     * @param UserAuthenticationService $authenticationService
     */
    public function __construct(
        UserAuthenticationService $authenticationService
    ) {
        $this->authenticationService = $authenticationService;
    }

    /**
     * __invoke
     *
     * @param null $default
     *
     * @return mixed|null
     */
    public function __invoke($default = null)
    {
        return $this->get($default = null);
    }

    /**
     * get
     *
     * @param null $default
     *
     * @return UserInterface|null
     */
    public function get($default = null)
    {
        return $this->authenticationService->getIdentity($default);
    }
}
