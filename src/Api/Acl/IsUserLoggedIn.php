<?php

namespace Rcm\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface IsUserLoggedIn
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request
    ):bool;
}
