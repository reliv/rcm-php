<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\User\Entity\UserInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface SetIdentity
{
    /**
     * Force a User into the auth'd session.
     * - WARNING: this by-passes the authentication process
     *            and should only be used with extreme caution
     *
     * @param ServerRequestInterface $request
     * @param UserInterface          $identity
     *
     * @return void
     */
    public function __invoke(
        ServerRequestInterface $request,
        UserInterface $identity
    );
}
