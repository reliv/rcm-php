<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ClearIdentity
{
    /**
     * Clears auth session (logs out user)
     *
     * @param ServerRequestInterface $request
     *
     * @return void
     */
    public function __invoke(
        ServerRequestInterface $request
    );
}
