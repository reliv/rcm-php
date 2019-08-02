<?php

namespace RcmUser\Api\Authentication;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface HasIdentity
{
    /**
     * Check if any User is auth'ed (logged in)
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request
    ): bool;
}
