<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsIdentity
{
    /**
     * Check if the requested user in the user that is currently in the auth session
     *
     * @param ServerRequestInterface $request
     * @param UserInterface          $user
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        UserInterface $user
    ): bool;
}
