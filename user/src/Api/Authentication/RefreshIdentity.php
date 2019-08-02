<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Exception\RcmUserException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface RefreshIdentity
{
    /**
     * Will reload the current User that is Auth'd into the auth'd session.
     * Is a way of refreshing the session user without log-out, then log-in
     *
     * @param ServerRequestInterface $request
     *
     * @return void
     * @throws RcmUserException
     */
    public function __invoke(
        ServerRequestInterface $request
    );
}
