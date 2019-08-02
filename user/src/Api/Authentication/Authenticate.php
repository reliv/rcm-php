<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\User\Entity\UserInterface;
use Zend\Authentication\Result;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface Authenticate
{
    /**
     * Creates auth session (logs in user)
     * if credentials provided in the User object are valid.
     *
     * @param ServerRequestInterface $request
     * @param UserInterface          $requestUser
     *
     * @return Result
     */
    public function __invoke(
        ServerRequestInterface $request,
        UserInterface $requestUser
    ): Result;
}
